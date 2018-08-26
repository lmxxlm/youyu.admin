<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|
|
|
*/
defined('LIFE_CYCLE')      		OR define('LIFE_CYCLE', 7200);
// defined('VALIDATE_TIME')      	OR define('VALIDATE_TIME', 7000);


/*
|	分页参数配置
*/
defined('PAGE_SIZE')      		OR define('PAGE_SIZE', 10);

/*
|
|	交易类型
*/
define('ACCOUNT_LOG_TYPE_RECHARGE', 'recharge');//充值
define('ACCOUNT_LOG_TYPE_BONUS', 'bonus');//充值(手工返现type)
define('ACCOUNT_LOG_REAL_TYPE_RECHARGE', 'm_recharge');//充值(线下充值real_type)
define('ACCOUNT_LOG_REAL_TYPE_BONUS', 'bonus_recharge');//充值(手工返现real_type)
define('ACCOUNT_LOG_REAL_TYPE_PAYBACK', 'payback_recharge');//充值(还款充值real_type)
define('ACCOUNT_LOG_REAL_TYPE_OTHER', 'other_recharge');//充值(其他real_type)

define('ACCOUNT_LOG_TYPE_CASH_APPLY', 'cash_apply');//提现
define('ACCOUNT_LOG_TYPE_CASH_SUCCESS', 'cash_success');//提现成功
define('ACCOUNT_LOG_TYPE_CASH_FAILURE', 'cash_failure');//提现失败
define('ACCOUNT_LOG_TYPE_CASH_CANCEL', 'cash_cancel');//取消提现
define('ACCOUNT_LOG_TYPE_CASH_FROST', 'cash_frost');//提现冻结

define('ACCOUNT_LOG_TYPE_TENDER', 'tender');//投资
define('ACCOUNT_LOG_TYPE_TENDER_RECEIVE', 'tender_receive');//借款人收款

define('ACCOUNT_LOG_TYPE_REPAYMENT', 'repayment');//借款人还款
define('ACCOUNT_LOG_TYPE_REPAYMENT_RECEIVE', 'repayment_receive');//投资人收款

define('ACCOUNT_LOG_TYPE_BORROW_SUCCESS', 'borrow_success');//借款成功

define('ACCOUNT_LOG_TYPE_REWARD_USE', 'reward_use'); //使用红包
define('ACCOUNT_LOG_TYPE_INVEST_FALSE', 'invest_false'); //投资失败资金
define('ACCOUNT_LOG_TYPE_LATE_REPAYMENT', 'late_repayment'); //逾期利息
define('ACCOUNT_LOG_TYPE_LATE_COLLECTION', 'late_collection'); //逾期收益
define('ACCOUNT_LOG_TYPE_RECHARGE_FEE', 'recharge_fee'); //提现手续费

//标真实状态【0等待审核,1审核失败,2投资中,3已满标,4已流标,5还款中,6已还款，7 满标待审】'
define('BORROW_STATE_CHECK_WAIT', 0);//等待审核
define('BORROW_STATE_CHECK_FAILURE', 1);//审核失败
define('BORROW_STATE_BIDDING', 2);//投资中
define('BORROW_STATE_BID_FULL', 3);//已满标
define('BORROW_STATE_BID_FAILURE', 4);//已流标
define('BORROW_STATE_REPAYING', 5);//还款中
define('BORROW_STATE_REPAID', 6);//已还款
define('BORROW_STATE_FULL_WAIT', 7);//满标待审

//还款方式
define('REPAYMENT_STYLE_ONCE', '1');//到期还本付息
define('REPAYMENT_STYLE_LAST_MONTH', '2');//到期还本按月付息
define('REPAYMENT_STYLE_MONTH', '3');//按月分期