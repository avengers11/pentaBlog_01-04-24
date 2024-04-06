<div class="col-lg-5">
    <table class="table table-striped" style="border: 1px solid #0000005a;">
        <thead>
            <tr>
                <th scope="col">{{ $keywords['Short_Code'] ?? __('Short Code') }}</th>
                <th scope="col">{{ $keywords['Meaning'] ?? __('Meaning') }}</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>{customer_name}</td>
                <td scope="row">{{ $keywords['Name_of_The_Customer'] ?? __('Name of The Customer') }}</td>
            </tr>

            @if ($templateInfo->email_type == 'email_verification')
                <tr>
                    <td>{verification_link}</td>
                    <td scope="row">{{ $keywords['Email_Verification_Link'] ?? __('Email Verification Link') }}</td>
                </tr>
            @endif

            @if ($templateInfo->email_type == 'package_purchase_notification' ||
                $templateInfo->email_type == 'payment_accepted_for_membership_offline_gateway')
                <tr>
                    <td>{package_name}</td>
                    <td scope="row">{{ $keywords['Name_of_The_Package'] ?? __('Name of The Package') }}</td>
                </tr>
                <tr>
                    <td>{package_start_date}</td>
                    <td scope="row">{{ $keywords['Start_Date_of_The_Package'] ?? __('Start Date of The Package') }}
                    </td>
                </tr>
                <tr>
                    <td>{package_expire_date}</td>
                    <td scope="row">{{ $keywords['Expire_Date_of_The_Package'] ?? __('Expire Date of The Package') }}
                    </td>
                </tr>
                <tr>
                    <td>{package_price}</td>
                    <td scope="row">{{ $keywords['Price_of_The_Package'] ?? __('Price of The Package') }}</td>
                </tr>
            @endif

            @if ($templateInfo->email_type == 'payment_rejected_for_membership_offline_gateway')
                <tr>
                    <td>{package_name}</td>
                    <td scope="row">{{ $keywords['Name_of_The_Package'] ?? __('Name of The Package') }}</td>
                </tr>
            @endif

            @if ($templateInfo->email_type == 'reset_password')
                <tr>
                    <td>{password_reset_link}</td>
                    <td scope="row">{{ $keywords['Password_Reset_Link'] ?? __('Password Reset Link') }}</td>
                </tr>
            @endif
            <tr>
                <td>{website_title}</td>
                <td scope="row">{{ $keywords['Website_Title'] ?? __('Website Title') }}</td>
            </tr>
        </tbody>
    </table>
</div>
