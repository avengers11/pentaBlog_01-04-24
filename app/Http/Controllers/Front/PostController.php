<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User\BasicSetting;
use App\Models\User\Information;
use App\Models\User\BookmarkPost;
use App\Models\User\Post;
use App\Models\User\PostCategory;
use App\Models\User\PostContent;
use App\Models\User\PostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function posts(Request $request,$domain)
    {
        $user = getUser();

        $language = $this->getUserCurrentLanguage($user->id);

        $queryResult['pageHeading'] = $this->getUserPageHeading($language,$user->id);

        $queryResult['bgImg'] = $this->getUserBreadcrumb($user->id);

        $queryResult['settings'] = BasicSetting::where('user_id', $user->id)
                                                ->select('post_view_type')
                                                ->first();

        $postTitle = $postCategory = null;

        if ($request->filled('title')) {
            $postTitle = $request->title;
        }

        if ($request->filled('category')) {
            $postCategory = $request->category;
        }

        $limit = 6;
        if (!empty($queryResult['settings']) && $queryResult['settings']->post_view_type == 'standard') {
            $limit = 3;
        }

        $queryResult['posts'] = DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $language->id)
            ->when($postTitle, function ($query, $postTitle) {
                return $query->where('post_contents.title', 'like', '%' . $postTitle . '%');
            })
            ->when($postCategory, function ($query, $postCategory) {
                return $query->where('post_contents.post_category_id', '=', $postCategory);
            })
            ->where('posts.user_id', '=', $user->id)
            ->orderBy('posts.serial_number', 'ASC')
            ->paginate($limit);

        if (Auth::guard('customer')->check() == true) {
            $authUser = Auth::guard('customer')->user();
            $queryResult['bookmarkPosts'] = BookmarkPost::where('user_id', $authUser->id)->get();
        }

        $queryResult['authorInfo'] = $this->getAuthorInfo($language,$user->id);

        $queryResult['popularPosts'] = $this->getPopularPosts($language,$user->id);

        $queryResult['categories'] = $this->getCategories($language,$user->id);

        return view('user-front.common.post.posts', $queryResult);
    }

    public function postDetails(Request $request,$domain,$slug)
    {

        $user = getUser();

        $language = $this->getUserCurrentLanguage($user->id);

        $queryResult['pageHeading'] = $this->getUserPageHeading($language,$user->id);

        $queryResult['bgImg'] = $this->getUserBreadcrumb($user->id);

        $post_id = PostContent::query()
                              ->where('slug', $slug)
                              ->where('user_id', $user->id)
                              ->select('post_id')
                              ->firstOrFail()
                              ->post_id;

        $details = PostContent::with('post')
            ->where('language_id', $language->id)
            ->where('post_id', $post_id)
            ->where('user_id',$user->id)
            ->firstOrFail();

        if (Auth::guard('customer')->check() == true) {
            $authUser = Auth::guard('customer')->user();
            $info = BookmarkPost::where('user_id', $authUser->id)
                ->where('post_id', $details->post_id)
                ->first();

            if (is_null($info)) {
                $queryResult['postBookmarked'] = 0;
            } else {
                $queryResult['postBookmarked'] = 1;
            }
        }

        $categoryId = PostContent::where('language_id', $language->id)
            ->where('post_id', $details->post_id)
            ->where('user_id',$user->id)
            ->pluck('post_category_id')
            ->first();


        $queryResult['relatedPosts'] = DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('post_contents.language_id', '=', $language->id)
            ->where('post_contents.post_category_id', '=', $categoryId)
            ->where('post_contents.post_id', '!=', $details->post_id)
            ->where('posts.user_id', '=', $user->id)
            ->orderBy('posts.serial_number', 'ASC')
            ->get();

        $queryResult['disqusInfo'] = BasicSetting::where('user_id', $user->id)
            ->select('disqus_status', 'disqus_short_name')
            ->first();

        $queryResult['authorInfo'] = $this->getAuthorInfo($language, $user->id);

        $queryResult['popularPosts'] = $this->getPopularPosts($language, $user->id);

        $queryResult['categories'] = $this->getCategories($language, $user->id);

        $queryResult['details'] = $details;


        $this->viewLog($request, $details->post_id,$user->id);
        return view('user-front.common.post.post-details', $queryResult);
    }

    /**
     * Get information of author.
     *
     * @param  int  $language
     * @return Information
     */
    public function getAuthorInfo($language,$userId)
    {
        return Information::where('language_id', $language->id)->where('user_id', $userId)->first();
    }

    /**
     * Get popular posts.
     *
     * @param  object  $language
     * @return
     */
    public function getPopularPosts($language, $userId)
    {
        return DB::table('posts')
            ->join('post_contents', 'posts.id', '=', 'post_contents.post_id')
            ->where('posts.views', '!=', 0)
            ->where('post_contents.language_id', '=', $language->id)
            ->where('posts.user_id', $userId)
            ->orderByDesc('posts.views')
            ->limit(3)
            ->get();

    }

    /**
     * Get all categories of post.
     *
     * @param  object  $language
     * @return
     */
    public function getCategories($language,$userId)
    {
        return PostCategory::where('language_id', $language->id)
            ->where('user_id', $userId)
            ->where('status', 1)
            ->orderBy('serial_number', 'asc')
            ->get();
    }

    /**
     * Create a view record for post.
     *
     * @param  int  $id
     * @return
     */
    public function viewLog($request, $postId, $authorId)
    {
        $postViews = PostView::where('post_id', $postId)->where('author_id', $authorId)->get();
        $ipAddress = $request->getClientIp();

        if (count($postViews) > 0) {
            foreach ($postViews as $view) {
                if (strcmp($view->ip, $ipAddress) == 0) {
                    return;
                }
            }
        }

        $postView = new PostView();
        $postView->post_id = $postId;
        $postView->user_id = Auth::guard('customer')->check() == true ? Auth::guard('customer')->user()->id : null;
        $postView->author_id = $authorId;
        $postView->ip = $ipAddress;
        $postView->save();

        // count total views of this post
        $post = Post::find($postId);
        $viewCount = PostView::where('post_id', $postId)
                             ->where('author_id', $authorId)
                             ->count();

        $post->update(['views' => $viewCount]);
        return;
    }

    /**
     * Create or remove bookmark.
     *
     * @param  int  $id
     * @return
     */
    public function makeBookmark($domain,$id)
    {
        $author = getUser();
        if (Auth::guard('customer')->check() == false) {
            return response()->json(['fail' => 'Please login before bookmark this post.']);
        } else {
            $user = Auth::guard('customer')->user();
            $bookmarkInfo = $user->bookmarkList()->where('post_id', $id)->first();

            if (is_null($bookmarkInfo)) {
                $bookmarkPost = new BookmarkPost();
                $bookmarkPost->user_id = $user->id;
                $bookmarkPost->post_id = $id;
                $bookmarkPost->author_id = $author->id;
                $bookmarkPost->save();

                $postBookmarks = $this->countPostBookmark($id);

                return response()->json([
                    'success' => 'Post bookmarked successfully!',
                    'status' => 'bookmarked',
                    'postId' => $id,
                    'totalBookmark' => $postBookmarks
                ]);
            } else {
                $bookmarkInfo->delete();

                $postBookmarks = $this->countPostBookmark($id);

                return response()->json([
                    'success' => 'Bookmark removed successfully!',
                    'status' => 'removed',
                    'postId' => $id,
                    'totalBookmark' => $postBookmarks
                ]);
            }
        }
    }

    /**
     * Count total bookmark of a post.
     *
     * @param  int  $id
     * @return
     */
    public function countPostBookmark($id)
    {
        // count total bookmarks of this post
        $post = Post::find($id);
        $bookmarkCount = BookmarkPost::where('post_id', $id)->count();
        $post->update(['bookmarks' => $bookmarkCount]);
        return $post->bookmarks;
    }
}
