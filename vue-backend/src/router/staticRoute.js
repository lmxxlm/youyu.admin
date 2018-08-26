const Layout = () => import(/* webpackChunkName: 'index' */ '../page/layout')

const staticRoute = [

    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/error',
        component: () => import(/* webpackChunkName: 'error' */ '../page/error'),
        children: [
            {
                path: '401',
                component: () => import(/* webpackChunkName: 'error' */ '../page/error/401')
            },
            {
                path: '403',
                component: () => import(/* webpackChunkName: 'error' */ '../page/error/403')
            },
            {
                path: '404',
                component: () => import(/* webpackChunkName: 'error' */ '../page/error/404')
            },
            {
                path: '500',
                component: () => import(/* webpackChunkName: 'error' */ '../page/error/500')
            }
        ]
    },
    {
        path: '/login',
        component: () => import(/* webpackChunkName: 'login' */ '../page/login')
    },
    {
        path: '/home',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: 'home' */ '../page/home'),
            }
        ]
    },
    {
        path: '/components',
        component: Layout,
        children: [
            {
                path: 'home',
                component: () => import(/* webpackChunkName: 'components' */ '../page/home'),  //注释webpackChunkName: 'components'这句话绝对不能去掉，会报错
            },
            //借款管理
            {
                path: 'borrow/repayList',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/borrow/repayList') //  还款计划
            },
            {
                path: 'borrow/borrowList',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/borrow/borrowList') //  借款列表
            },
            //提现管理
            {
                path: 'withdraw/withdrawList',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/withdraw/withdrawList') //  提现列表
            },
            //资金管理
            {
                path: 'capital/overview',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/overview') //  资金总览
            },
            {
                path: 'capital/moneyReport',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/moneyReport') //  资金日报
            },
            {
                path: 'capital/rechargeCount',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/rechargeCount') //  充值资金
            },
            {
                path: 'capital/loanList',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/loanList') //  贷款资金
            },
            {
                path: 'capital/investList',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/investList') //  投资明细表
            },
            {
                path: 'capital/rewardReport',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/rewardReport') //  红包日报数据 / 红包日报表
            },
            {
                path: 'capital/projectCount',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/projectCount') //  项目信息统计
            },
            {
                path: 'capital/test',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/test') //  资金管理
            },
            {
                path: 'capital/addUserCouponBatch',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/addUserCouponBatch') //  批量新增年化券
            },
            {
                path: 'capital/userCouponList',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/userCouponList') //  年化券列表
            },
            {
                path: 'capital/getRecordCount',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/getRecordCount') //  月账单
            },
            {
                path: 'capital/accountList',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/accountList') //  资金账户列表
            },
            {
                path: 'capital/rechargeList',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/rechargeList') //  充值列表
            },
            {
                path: 'capital/sendRewardBatch',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/sendRewardBatch') //  发放红包
            },
            {
                path: 'capital/sendReward',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/sendReward') //  發送紅包
            },
            {
                path: 'capital/rewardUseList',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/rewardUseList') //  红包投资列表
            },
            {
                path: 'capital/reward',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/capital/reward') //  红包管理
            },
            {
                path: 'customer/bank',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/customers/bank') //  银行卡
            },
            {
                path: 'customer/downList',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/customers/downLists') //  下线列表
            },
            {
                path: 'customer/lists',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/customers/lists') //  客户列表
            },
            {
                path: 'customer/capital',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/customers/capital')//  资金列表
            },
            {
                path: 'customer/company',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/customers/company') //  企业管理列表
            },
            {
            	name: 'constPage',
                path: 'system/constPage',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/system/constPage')
            },
            {
                path: 'system/consts',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/system/const')
            },
            {
                path: 'system/params',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/system/params')
            },
            {
                path: 'system/manager',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/system/manager')
            },
            {
                path: 'system/role',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/system/role')
            },
            {
                path: 'system/cache',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/system/cache')
            },
            {
                path: 'system/picture',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/system/picture')
            },

            {
                path: '',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components')
            },
            {
                path: 'pageNotes',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/assist/pageNotes')
            },
            {
                path: 'permission',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/function/permission')
            },
            {
                path: 'pageTable',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/function/pageTable')
            },
            {
                path: 'pageSearch',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/ui/pageSearch')
            },
            {
                path: 'pageSection',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/ui/pageSection')
            },
            {
                path: 'pageTitle',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/ui/pageTitle')
            },
            {
                path: 'pageToolbar',
                component: () => import(/* webpackChunkName: 'components' */ '../page/components/ui/pageToolbar')
            }
        ]
    },
    {
        path: '/example',
        component: Layout,
        children: [
            {
                path: 'table',
                component: () => import(/* webpackChunkName: 'example' */ '../page/example/table')
            },
            {
                path: 'charts',
                component: () => import(/* webpackChunkName: 'example' */ '../page/example/charts')
            },
            {
                path: 'map',
                component: () => import(/* webpackChunkName: 'example' */ '../page/example/map')
            }
        ]
    },
    {
        path: '/i18n',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: 'i18n' */ '../page/i18n')
            }
        ]
    },
    {
        path: '/theme',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: 'themeChange' */ '../page/themeChange')
            }
        ]
    },
    {
        path: '*',
        redirect: '/error/404'
    }
]

export default staticRoute
