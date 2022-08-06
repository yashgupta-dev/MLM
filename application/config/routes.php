<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['captcha'] ='welcome/captcha';
$route['get/apifunctiongetdata'] ='welcome/apifunction_getdata';
$route['get/apifunctiongetdataLeftCount'] ='welcome/apiFunctionCuntLeftData';
$route['get/apiGetRightBusniness'] ='welcome/getRightBusinessCountData';
$route['get/apiGetLeftBusniness'] = 'welcome/leftBusinessTotalShow';
$route['get/left-team-data'] ='welcome/leftTeamDataApi';
$route['customer/login-to-account']='login/accountLogin';
$route['customer/create-new-account']='login/accountCreate';
$route['customer/Register-New/(:any)'] ='login/accountCreate';
$route['signu_up_me']='login/accountCreateme';
$route['customer/forget-password']='login/forgetpassword';
$route['forgetCheckMember']='login/checkMemberIdFunction';
$route['otp_verify'] ='login/otp_verifyFunction';
$route['change_password'] ='login/updatePasswordFunction';
$route['login']='login/logintome';
$route['my/dashboard']='dashboard/user';
$route['my/team/Genealogy']='dashboard/userGenealogy'; 
$route['my/log-out']='login/logoutuser';
$route['my/profile/update-password']='dashboard/password_update';
$route['my/profile/update-kyc'] ='dashboard/update_kyc_form';
$route['my/kyc/form'] ='dashboard/kyc_data';

$route['my/test'] ='dashboard/testFunction';

$route['my/income'] ='dashboard/incomeFunctionBig';
$route['removeImageProof'] ='dashboard/removeImageProodyFunction';
//$route['updatewallet'] ='dashboard/updateWalletalcnce';
$route['account-information-update']='dashboard/upadate_account';
$route['account-information-Add']='dashboard/add_account';
$route['profile-information-update']='dashboard/upadate_profile';
$route['contact-information-update']='dashboard/upadate_contact';
$route['nominee-update']='dashboard/upadate_nominee';
$route['activation']='dashboard/member_activate';
$route['my/team/Sponser-list']='dashboard/sponser_list_count';

$route['my/team']='dashboard/right_team_count';
$route['my/teamFetchShowMe'] = 'dashboard/fetchTeamRecrdsods';
$route['myteamFetch/ajaxPaginationData/(:any)'] = 'dashboard/fetchUsingAjaxRecord';
//$route['my/team']='dashboard/left_team_count';
$route['my/profile/update-profile']='dashboard/profile_update';
$route['ftech_username'] ='welcome/fetchUsernameSponsertPage';
$route['my/scratch']='dashboard/scratch_card_details';
$route['bulk-create-user'] ='login/adminaccountCreateme';

$route['admin/set_default_primary'] ='admin/set_primary';
$route['admin/make-binary-user'] ='binary/run';
$route['admin/manage-account'] = 'admin/openbanknDetailsfunction';
$route['fetch_child']='dashboard/childTwoMore';
$route['show_data_onhover']='dashboard/fetch_binary';
$route['my/Binary-macthing']='dashboard/matchngincome';
$route['my/daily-payout-left']='dashboard/dailypautoutleft';
$route['ROIIncrease']='dashboard/ROIIncrease';
$route['coundown']='dashboard/coundown';
$route['update_password']='dashboard/update_password';
$route['requestToId']='dashboard/requestToId';
$route['requestToIdFinal']='dashboard/requestToIdFinal';
$route['cancel_order'] ='dashboard/cancelFunction';
$route['bookOrder'] ='dashboard/bookOrderFunctionCheck';
$route['my/transaction'] ='dashboard/transactionRequestDetails';
$route['my/trnsactionPageMain'] ='dashboard/transactionPagedata';
$route['my/transactionAjax/ajaxPaginationData/(:any)'] ='dashboard/transactionAjaxFunction';

$route['my/app_swicthAccess'] = 'dashboard/appaccessswitchcombination';

$route['my/team/Direct-sponser'] ='dashboard/direct_sponser_table';
$route['admin/admin-check/(:any)']='admin/showme/$1';

$route['admin/bank-delete'] ='admin/admin_bank_deleteme';



$route['my/upgrade'] ='dashboard/upgradeFunction';

$route['scratchDefault'] ='dashboard/scratchAllData';
$route['scratchDefaultAjax/ajaxPaginationData/(:any)'] = 'dashboard/scarcthDataAjaxPaginate';

$route['e_requestFunction'] ='dashboard/all_requestUser';
$route['e_requestUserAjax/ajaxPaginationData/(:any)'] ='dashboard/e_request_ajax_show';

$route['makeURI'] ='tools/index';
$route['RightTeamCount'] ='dashboard/countRightTeam';

$route['Daily_payout_update'] ='dashboard/DailyPayoutUpdate';
$route['my/rewards'] = 'dashboard/rewardsFunction';

$route['makeIncome'] ='dashboard/makeBinaryIncome';
//$route['my/widthdrow'] ='dashboard/widthdrowFunction';
//$route['interest'] ='dashboard/interestFunction';
//$route['transfer-money'] ='dashboard/trsnferMoneyFunction';
//$route['otpVerifyFunction'] ='dashboard/otpVerify_function_check';


///// admin route----
$route['admin'] = 'login/adminLogin';
$route['admin-login'] ='login/admin_sign_in';
$route['admin/dashboard']='admin/index';
$route['admin/log-out']='login/logoutuser';
$route['admin/site-setting'] = 'admin/site_setting';
$route['seo'] ='admin/seo_done';
$route['seo_fetch'] = 'admin/fetch_seo';
$route['fetch_title_site'] ='admin/fetch_title';
$route['site-Title'] ='admin/update_title';
$route['upload_favicon'] ='admin/upload_favicon_img';
$route['upload_logo'] ='admin/upload_logo_img';
$route['loaderUpload'] ='admin/loader_update';
$route['fetch_favicon_logo'] ='admin/favicon_img';
$route['default_user'] ='admin/site_default_user';
$route['admin/scratch-request']='admin/scratch_id';
$route['accept_id']='admin/accept_id';
$route['reject_id'] = 'admin/reject_idFunction';
$route['admin/all-user']='admin/all_user_show';
$route['scratchDefaultrequest'] ='admin/scratchAllDataRequest';
$route['scratchDefaultAjaxRequest/ajaxPaginationData/(:any)'] ='admin/scarcthDataAjaxPaginateRequest';

$route['showUserPag'] = 'admin/all_userDataPagination';
$route['allUserPagnet/ajaxPaginationData/(:any)'] = 'admin/alllPaginationSecond';

$route['admin/payout'] ='admin/payoutFunction';
$route['allDailypayR'] ='admin/AllPayDataPagination';
$route['allpayoutData/ajaxPaginationData/(:any)'] ='admin/payoutAjaxdata';

$route['allbinaryroute/ajaxPaginationData/(:any)'] ='admin/binaryAjaxFunction';

$route['admin/payment-control'] ='admin/single_legFunction';
$route['admin/package-manage'] ='admin/changePackageFunctionAndEdit';
$route['admin/package_change-edit'] = 'admin/changepackageinformation';
$route['admin/add-row-new'] ='admin/addNewRowFunctionCreate';
$route['admin/add-row-new-remove'] = 'admin/removeNewRowFunction';
$route['singledataLeg'] ='admin/allSIngleLeg';
$route['singleLefAjax/ajaxPaginationData/(:any)'] ='admin/singleAjaxDataPaginate';

$route['admin/user-kyc'] ='admin/kyc_page';
$route['show-kyc'] ='admin/kycfunction';
$route['kycAjax/ajaxPaginationData/(:any)'] ='admin/kycAJaxFunction/$1';

$route['verfiyDocs'] ='admin/verifyKycFormDone';
$route['savedata'] ='admin/verifyKycObject';
$route['notification'] ='admin/fetchNotification';

$route['admin/transaction-request'] ='admin/transactionPage';
$route['transactionpageFunction'] ='admin/transationOageFuncgugihjknj';
$route['transactionAjax/ajaxPaginationData/(:any)'] ='admin/transactionAJaxRequest';

$route['admin/rate-manage'] ='admin/rateFunction';
$route['ratechange'] ='admin/rateFuntionNew';


$route['admin/withdrawPaymentOption'] = 'admin/withdraw_onOffOptionFunctionPage';
$route['admin/income'] ='admin/incomeAdminPage';
$route['royalUser'] ='admin/royalFunction';
$route['royalAjax/ajaxPaginationData/(:any)'] ='admin/royalAjaxFunction';

$route['directIncomeFunc'] ='admin/direct_income_function';
$route['directAjax/ajaxPaginationData/(:any)'] ='admin/directAjax';

$route['member-activate'] ='dashboard/activateFunction';

$route['checkemail'] ='login/checkemail';
$route['admin/rewards'] = 'admin/rewardsFunction';

$route['admin/tournament'] ='admin/tournamentFunction';
$route['game/add-function'] ='admin/add_gameFunctionData';
$route['admin/startGame'] ='admin/start_gameToFunction';

$route['admin/create-user-bulk'] ='admin/bulkUserCreateFunction';
$route['admin/winners-annoucment'] ='admin/winners_functionTo_GetData';
$route['admin/winners_secondList'] ='admin/secondWinnerdata';
$route['admin/SendTo_Winners'] ='admin/SendTo_WinnersFunction';

$route['admin/binary-income'] ='admin/binaryIncomeTableView';
$route['admin/binaryTableMain'] ='admin/binaryTableCreateFunction';
$route['admin/binaryAjax/ajaxPaginationData/(:any)'] ='admin/binaryTableNewCreateAjax';

$route['admin/all-bank-accounts'] ='admin/fetchBankFunctoion';
$route['admin/add_transaction_admin'] = 'admin/admin_add_debit_creditFunction';
$route['admin/debit-and-credit-transaction'] = 'admin/admin_debit_creditFunction';
$route['admin/deactivePage'] ='admin/deactiveFunctionPage';
$route['admin/activePage'] ='admin/activePageFunction';
$route['admin/change'] ='admin/pckage_changeFunction';
$route['admin/changenow_package'] ='admin/changePkgFunction';
$route['admin/blockUser'] ='admin/blockFunctionPage';
$route['admin/unblockpage'] = 'admin/unblockPageFunction';
$route['admin/edit'] ='admin/editChangeFunction';
$route['admin/edit-user-details'] ='admin/usereditFunctionPageControl';
$route['admin/kyc.verify.all'] ='admin/approveFunctionCreate';
$route['admin/kyc.approve'] ='admin/approveKycNowFunctionCreate';
$route['admin/kyc.reject'] ='admin/rejectKycNowFunctionCreate';
$route['admin.manage/database'] ='admin/manageDatabaseNow';
$route['admin.manage/database/table'] = 'admin/downloadTableFunction';
$route['admin/reward-edit'] ='admin/rewardEditFunctionCreate';
$route['admin/payment-control-edit'] ='admin/paymentControlEditFunctionCreate';
$route['admin/daily-payout'] ='admin/dailyPayoutOnOffFunctionCreate';
$route['admin/auto'] = 'admin/test';

$route['api/form'] ='Game/index';
$route['api/create-account'] ='Game/create_new_account';
$route['api/signupToCreate'] ='Game/createNew_accountImmediate';
$route['api/finishAccountSetup'] ='Game/checkToFinsihAccount';
$route['api/forget-password'] ='Game/forgetpasswordPageFunction';
$route['api/otp_verify'] ='Game/otp_verifyCheck';
$route['api/compelet-registration'] = 'Game/complete_registrationForm';
$route['api/login'] ='Game/loginTOAccount';
$route['api/log-out'] ='Game/logoutFunction';
$route['api/game'] ='GameController/index';
$route['api/change-password'] ='GameController/change_passwordPageFunction';
$route['api/notification'] ='GameController/notificationPage';
$route['api/requestTo'] ='GameController/balance_addFunctionPhp';
$route['api/moneysuccess'] ='GameController/responseSuccessPage';
$route['api/add-balance'] ='GameController/balanceFunctionPhp';
$route['api/account-profile'] = 'GameController/accountHistory';
$route['api/my-matches'] ='GameController/matchesResultSHow';
$route['api/more-info'] ='GameController/moreInfoFunction';
$route['api/start_gameData'] ='GameController/gameStart';
$route['api/game_data_save'] ='GameController/saveDataGame';
$route['api/gameWorld'] = 'GameController/gameWorldFunction';
$route['api/play-game'] ='GameController/play_gameNow';
$route['api/gameEnterTo'] ='GameController/checkProbalityToGame';
$route['api/join/tournament'] ='GameController/joinGameFunctionUser';
$route['api/change_password_page'] ='GameController/update_passwordFunctionPage';
$route['api/cutinterest'] ='GameController/makeInterestFunctioon';
$route['api/transferMoneyRequest'] ='GameController/transferMoneyRequestto';

//$route['api/otp_verifyFunctionPage'] ='GameController/otp_verifyFunctionPageFunction';
$route['api/forgetCheckMember'] ='Game/foregtFunction';
//$route['api/otp_verify'] ='Game/otp_verifyFunctionPageController';
$route['api/change_passwordapi'] ='Game/chnagePasswordFunctionTo';
$route['api/transaction_history'] ='GameController/showTransactionhistoryPage';
$route['api/widthdrawlHistory'] ='GameController/showWithdrawlPageFunction';
$route['api/join-contest'] ='GameController/join_contestNowFunction';
$route['api/form-procceedto'] ='GameController/finalPaymentOption';
$route['api/upgradeform'] ='GameController/upgradefunctionpage';
$route['api/upgrade'] ='GameController/upgradeNowFunctionPage';

$route['api/withdrow-amount'] ='GameController/withdrawFnctionPage';
$route['api/add-benificery'] ='GameController/addBenificeryPageInserted';
$route['api/add-benificery-account'] ='GameController/makeValidationBeniicery';
$route['api/withdrawlForm'] ='GameController/withdrawPageFuntionTransaction';
$route['api/complete-kyc'] ='GameController/do_kycUpgradeFunction';
$route['api/complete-kyc-data'] ='GameController/completeKycFinal';
$route['api/account-again-exist'] = 'GameController/createAccountAgain';

$route['api/testData'] = 'GameController/test';
$route['api/view-mega-contest-all'] ='GameController/viewALldataToMEFunction';
$route['api/app-setting'] ='GameController/settingAPPFunction';