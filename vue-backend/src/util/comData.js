//系统参数
export function paramsData(){
  var data={
    modelTitle:'添加系统参数',
    submitStatus:'add',
    searchForm: {
          size:10,//每页多少条
          page:1,//当前页
          total:0,//总共多少条
          search: ''
     },
     tableData: {
              loading: true,
              head: [
                 {key:'style',name:'分组'},
                 {key:'name',name:'名称'},
                 {key:'nid',name:'代码'},
                 {key:'value',name:'值'}
              ],
              body: []
         },
         modelTitle:'添加常量类型',
         isModelShow:false,
         ruleForm: {
          name: '',
          nid: '',
          value: '',
          type:'',
          style:'',
          status:''
        },
        rules: {
          name: [
            { required: true, message: 'name 必须填写.', trigger: 'blur' },
            { min: 0, max: 30, message: '长度在 0到30字符', trigger: 'blur' }
          ],
          nid: [
            { required: true, message: '字符nid 必须填写', trigger: 'blur' },
            { min: 0, max: 50, message: '长度在 0到50字符', trigger: 'blur' }
          ],
          value: [
            { required: true, message: 'order 必须填写', trigger: 'blur' },
            { type: 'string',message: '*',trigger: 'blur' }
          ],
          type: [
            { required: true, message: '0.小框，1.单选，2.文本，3.图片.', trigger: 'blur' },
          ],
          style: [
            { required: true, message: '必须', trigger: 'blur' },
          ],
          status: [
            { required: true, message: '0.不可删除', trigger: 'blur' }
          ]
        },
  }
  return data
}

//常量类型配置
export function constData(){
    var data ={
      modelTitle:'添加系统参数',
      submitStatus:'add',
      searchForm: {
            size:10,//每页多少条
            page:1,//当前页
            total:0,//总共多少条
            search: ''
       },
       tableData: {
                loading: true,
                head: [
                   {key:'id',name:'id'},
                   {key:'name',name:'name'},
                   {key:'nid',name:'nid'},
                ],
                body: []
           },
           modelTitle:'添加常量类型',
           isModelShow:false,
           ruleForm: {
            name: '',
            nid: '',
            order: '',
          },
          rules: {
            name: [
              { required: true, message: 'name 必须填写.', trigger: 'blur' },
              { min: 0, max: 30, message: '长度在 0到30字符', trigger: 'blur' }
            ],
            nid: [
              { required: true, message: '字符nid 必须填写', trigger: 'blur' },
              { min: 0, max: 50, message: '长度在 0到50字符', trigger: 'blur' }
            ],
            order: [
              { required: true, message: 'order 必须填写', trigger: 'blur' },
              { type: 'string',message: '*',trigger: 'blur' }
            ],
          },
    }
    return data
}

//常量页面
export function constPageData(){
  var data ={
    modelTitle:'添加系统参数',
    submitStatus:'add',
    searchForm: {
          size:10,//每页多少条
          page:1,//当前页
          total:0,//总共多少条
          id:'',
          search: ''
     },
     tableData: {
              loading: true,
              head: [
                 {key:'id',name:'id'},
                 {key:'status',name:'status'},
                 {key:'order',name:'order'},
                 {key:'type_id',name:'type_id'},
                 {key:'pid',name:'pid'},
                 {key:'name',name:'name'},
                 {key:'value',name:'value'},
                 {key:'addtime',name:'addtime'},
                 {key:'addip',name:'addip'},
              ],
              body: []
         },
         modelTitle:'添加常量类型',
         isModelShow:false,
         ruleForm: {
          status: '',
          order: '',
          type_id: '',
          pid: '',
          name: '',
          value	: ''
        },
        rules: {
          status: [
            { required: true, message: '0字符 用户名不能修改status', trigger: 'blur' },
          ],
          order: [
            { required: true, message: '*0字符 用户名不能修改order', trigger: 'blur' },
          ],
          type_id: [
            { required: true, message: '*0字符 用户名不能修改type_id', trigger: 'blur' },
          ],
          pid: [
           { required: true, message: '*60字符 用户名不能修改pid', trigger: 'blur' },
         ],
         name: [
           { required: true, message: '*500字符 用户名不能修改name', trigger: 'blur' },
         ],
         value: [
           { required: true, message: '*500字符 用户名不能修改value', trigger: 'blur' },
         ]
        },
  }
  return data
}

//资金列表
export function capitalData(){
   var data ={
     id:'',
     multipleSelection: [], //被选中的删除项
     modelTitle:'添加系统参数',
     submitStatus:'add',
     searchForm: {
       total:0,
       options:[],
       page:1,
       size:10,
       money:'',
       type:'',
       start_time:'',
        end_time:'',
        activeTime:'',
        id:'',
      },
      tableData: {
         loading: true,
         head: [
            {key:'id',name:'用户ID'},
            {key:'status',name:'交易时间'},
            {key:'order',name:'操作金额'},
            {key:'type_id',name:'账户总额'},
            {key:'pid',name:'可用余额'},
            {key:'name',name:'冻结金额'},
            {key:'value',name:'待收金额'},
            {key:'addtime',name:'产生收益'},
            {key:'addip',name:'红包金额'},
            {key:'addtime',name:'交易对象'},
            {key:'addip',name:'交易类型'},
            {key:'addtime',name:'备注'}
         ],
         body: []
    },

    tableAcountData: {
           loading: true,
           head: [
              {key:'user_id',name:'用户ID'},
              {key:'total',name:'总额'},
              {key:'use_money',name:'	可用'},
              {key:'tender_freeze',name:'冻结'},
              {key:'wait_capital',name:'待收本金'},
              {key:'wait_interest',name:'待收利息'},
              {key:'taste_money',name:'	网银充值'},
              {key:'no_use_money',name:'已用红包金额'},
           ],
           body: []
      }
   }
 return data
}

//企业列表
export function companyListData(){
  var data={
    isVIP:false,
    disable:true,//按钮的禁用状态标志
    multipleSelection: [], //被选中的删除项
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    searchForm: {
          total:0,//总共多少条
          size:10,//每页多少条
          page:1,//当前页
          options: [
            {type:'',name:'请选择'},
            {type:'0',name:'已实名'},
            {type:'1',name:'未实名'},
          ],
          activeTime:[],//搜索的开始时间和结束时间
          username:'',
          realname:'',
          start_time:'',
          end_time:'',
          real_status:'',//全部状态
     },
     tableData: {
          loading: true,
          head: [
             {key:'user_id',name:'ID'},
             {key:'username',name:'用户名'},
             {key:'realname',name:'企业名'},
             {key:'linkman1',name:'联系人'},
             {key:'relation1',name:'职位'},
             {key:'tel1',name:'电话'},
             {key:'addtime',name:'添加时间'},
             {key:'status',name:'状态'},
             {key:'type_id',name:'类型'},
             {key:'type_name',name:'用户类型'},
             {key:'trackid',name:'渠道'},
          ],
          body: []
         },
         modelTitle:'添加常量类型',
         isModelShow:false,
         citys:[],
        ruleForm: {
          litpic:'',//图片路径
          ability:'',
          id:'',
          realname:'',
          company_worktime1:'',
          company_worktime2:'',
          private_employee:'',
          linkman1:'',
          relation1:'',
          tel1:'',
          company_reamrk:'',
          company_name:'',
          private_commerceid:'',
          province:'',
          city:'',
          area:'',
          address:'',
          others:'',
          status:'',
          real_status:''
        },
        rules: {
          id: [ { required: true, message: 'id 必须填写.', trigger: 'blur' }],
          realName: [ { required: true, message: '企业名称不能为空', trigger: 'blur' }],
          linkman1: [ { required: false, message: '企业联系人不能为空！', trigger: 'blur' } ],
          company_name: [ { required: true, message: '法人代表不能为空!', trigger: 'blur' } ],
          private_commerceid: [ { required: true, message: '税号不能为空！', trigger: 'blur' } ],
          relation1: [ { required: true, message: '联系人职位不能为空!', trigger: 'blur' } ],
       },

  }

  return data
}


//客户列表
export function customerListData(){
  var data={
    isVIP:false,
    disable:true,//按钮的禁用状态标志
    multipleSelection: [], //被选中的删除项
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    searchForm: {
      size:10,//每页多少条
      page:1,//当前页
      total:0,//总共多少条
      options: [
        {type:'user_id',name:'用户id'},
        {type:'username',name:'用户名'},
        {type:'realname',name:'真实姓名'},
        {type:'invite_userid',name:'上线用户ID'}
      ],
      activeTime:[],//搜索的开始时间和结束时间
      sts:[{type:0,name:'请选择'},{	type:1,name:'未实名'},{type:2,name:'已实名'}],
      banks:[{type:0,name:'请选择'},{type:1,name:'未绑卡'},{type:2,name:'已绑卡'	}],
      type:'',//搜索类型
      search: '',//搜索内容
      start_time:'',
      end_time:'',
      st:0,
      bank:0,
     },
     tableData: {
          loading: true,
          head: [
             {key:'user_id',name:'ID'},
             {key:'username',name:'用户名'},
             {key:'realname',name:'真实姓名'},
             {key:'sex',name:'性别'},
             {key:'email',name:'邮箱'},
             {key:'phone',name:'手机'},
             {key:'card_id',name:'身份证'},
             {key:'addtime',name:'添加时间'},
             {key:'is_company',name:'类型'},
             {key:'invite_userid',name:'上线用户ID'},
             {key:'invite_real',name:'上线用户名称'},
             {key:'app_marketing',name:'推广标识'},
             {key:'bank_status',name:'是否绑卡'},
          ],
          body: []
         },
         modelTitle:'添加常量类型',
         isModelShow:false,
        ruleForm: {
            id:'',
            username: '',
            realname: '',
            card_id: ''
        },
        rules: {
          username: [ { required: true, message: 'name 必须填写.', trigger: 'blur' }],
          realName: [ { required: true, message: 'realName 必须填写', trigger: 'blur' }],
          card_id: [ { required: false, message: 'card_id 必须填写', trigger: 'blur' } ]
       },
       ruleFormVIP:[
           {label:'用户名(手机号)',val:'18368839220'},
           {label:'检测结果',val:'条件不足'},
           {label:'资金账户',val:'0.01+0.01'},
           {label:'推荐投资用户',val:'0'}
       ]
  }

  return data
}


//下线列表
export function downListData(){
  var data={
    isVIP:false,
    disable:true,//按钮的禁用状态标志
    multipleSelection: [], //被选中的删除项
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    size:10,//每页多少条
    page:1,//当前页
    total:0,//总共多少条
    searchForm: {
          options: [
            {type:'user_id',name:'用户id'},
            {type:'username',name:'用户名'},
            {type:'realname',name:'真实姓名'},
            {type:'invite_userid',name:'上线用户ID'}
          ],
          activeTime:[],//搜索的开始时间和结束时间
          sts:[{type:0,name:'请选择'},{	type:1,name:'未实名'},{type:2,name:'已实名'}],
          banks:[{type:0,name:'请选择'},{type:1,name:'未绑卡'},{type:2,name:'已绑卡'	}],
          type:'',//搜索类型
          search: '',//搜索内容
          start_time:'',
          end_time:'',
          st:0,
          bank:0,
     },
     tableData: {
          loading: true,
          head: [
             {key:'invite_name',name:'上线用户名'},
             {key:'invite_real',name:'上线真实姓名'},
             {key:'invite_userid',name:'上线用户ID'},
             {key:'username',name:'下线用户名称'},
             {key:'realname',name:'下线真实名称'},
             {key:'addtime',name:'注册时间'},
             {key:'bank_status',name:'是否绑卡'},
             {key:'trackid',name:'渠道'}
          ],
          body: []
         },
         modelTitle:'添加常量类型',
         isModelShow:false,
        ruleForm: {
            invite_userid:''
        },
        rules: {
          invite_userid: [ { required: true, message: 'name 必须填写.', trigger: 'blur' }],
       },
       ruleFormVIP:[
           {label:'用户名(手机号)',val:'18368839220'},
           {label:'检测结果',val:'条件不足'},
           {label:'资金账户',val:'0.01+0.01'},
           {label:'推荐投资用户',val:'0'}
       ]
  }

  return data
}


//银行列表
export function bankData(){
  var data={
    isVIP:false,
    disable:true,//按钮的禁用状态标志
    multipleSelection: [], //被选中的删除项
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    searchForm: {
          page:1,
          size:10,
          user_id:'',
          bank_status:0,
          bank_code:'',
          bank_account:'',
          username:'',
          realname:'',
          addip:'',
          bankStatus:[
            {type:0,val:'状态未定'},
            {type:1,val:'有效'},
            {type:2,val:'未定'}
          ],
          bank_code:[
            {type:'',name:''}
          ],
     },
     tableData: {
          loading: true,
          head: [
             {key:'user_id',name:'用户ID'},
             {key:'bank_status',name:'银行卡绑定状态'},
             {key:'bank',name:'银行'},
             {key:'account',name:'银行卡号'},
             {key:'province',name:'开户省份'},
             {key:'city',name:'开户城市'},
             {key:'addtime',name:'添加时间'},
             {key:'addip',name:'添加IP'},
             {key:'username',name:'用户名'},
             {key:'realname',name:'用户姓名'},
             {key:'pay_status',name:'充值状态'}
          ],
          body: []
         },
         modelTitle:'银行号修改',
         isModelShow:false,
          ruleForm: {
              id:'',
              account:''
          },
          rules: {
            account: [ { required: true, message: '银行卡号必须填写.', trigger: 'blur' }],
         }

  }

  return data
}

//红包列表
export function rewardData(){
  var data={
    pNum:0,//人数
    money:0,//金额
    activeName: '1',
    curTitle:'全部红包',//默认头部的title
    activeName: 'first',
    activeNames:['1'],
    disable:true,//按钮的禁用状态标志
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    counts:'',
    searchForm: {
          size:10,//每页多少条
          page:1,//当前页
          total:0,//总共多少条
          user_id:'',
          username:'',
          money:'',
          get_time_begin:'2018-01-01',
          get_time_end:'2018-07-31',
          use_time_begin:'',
          use_time_end:'',
          use_together:'',//类型
          types:[
            {type:'',val:'类型'},
            {type:'1',val:'现金券'},
            {type:'2',val:'红包券'}
          ],
          reward_name:'',//快速检索来源
          sources:[],
          use_type:'',//全部状态
          status:[
            {type:'',val:'全部状态'},
            {type:'1',val:'未使用'},
            {type:'2',val:'已使用'},
            {type:'3',val:'已过期'},
            {type:'4',val:'可用'},
            {type:'5',val:'冻结'},
          ],
           setTime:[],
           useTime:[],
     },
     tableData: {
          loading: true,
          head: [
             {key:'user_id',name:'用户ID'},
             {key:'username',name:'用户名'},
             {key:'reward_no',name:'	编号'},
             {key:'invite_userid',name:'	渠道ID'},
             {key:'money',name:'	金额(元)'},
             {key:'reward_name',name:'来源活动'},
             {key:'money_limit',name:'红包限额'},
             {key:'borrow_days',name:'天数限额'},
             {key:'weixin_id',name:'	赠送的微信号'},
             {key:'use_together',name:'类型'},
             {key:'addtime',name:'获取时间'},
             {key:'timeout',name:'失效时间'},
             {key:'usetime',name:'使用时间'},
              {key:'is_use',name:'状态'},
          ],
          body: []
    },
  }

  return data
}

//红包投资列表
export function rewardUseListData(){
  var data={
    pNum:0,//人数
    money:0,//金额
    invest_count:0,//投资金额
    achive_count:0,//业绩总额
    activeName: '1',
    curTitle:'全部红包',//默认头部的title
    activeName: 'first',
    activeNames:['1'],
    disable:true,//按钮的禁用状态标志
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    counts:'',
    searchForm: {
          size:10,//每页多少条
          page:1,//当前页
          total:0,//总共多少条
          user_id:'',
          borrow_id:'',//标ID
          tender_id:'',//投资ID
          money:'',//红包金额
          username:'',//手机号
          get_time_begin:'',
          get_time_end:'',
          use_time_begin:'',
          use_time_end:'',
          use_together:'',//类型
          types:[
            {type:'',val:'类型'},
            {type:'1',val:'现金券'},
            {type:'2',val:'红包券'}
          ],
          reward_name:'',//快速检索来源
          sources:[],
          setTime:[],
          useTime:[],
     },
     tableData: {
          loading: true,
          head: [
             {key:'user_id',name:'用户ID'},
             {key:'username',name:'用户名'},
             {key:'regtime',name:'注册时间'},
             {key:'reward_no',name:'编号'},
             {key:'money',name:'	金额(元)'},
             {key:'reward_name',name:'来源活动'},
             {key:'weixin_id',name:'	赠送的微信号'},
             {key:'use_together',name:'类型'},
             {key:'addtime',name:'获取时间'},
             {key:'usetime',name:'使用时间'},
             {key:'borrow_id',name:'标ID'},
              {key:'tender_id',name:'投资ID'},
              {key:'tmoney',name:'投资金额'},
             {key:'performance',name:'业绩'},
          ],
          body: []
    },
  }

  return data
}

//发送给红包
export function sendRewardData(){
  var data={
    pickerOptions2:{
      disabledDate(time) {
         return time.getTime() <= (new Date().getTime());
      }
    },
    ruleForm: {
      use_together:'',
      username:'',
      money:'',
      timeout:'',
      reward_name:'',
      money_limit:'',
      borrow_days:''
    },
    rules: {
      use_together: [
        { required: true, message: '请选择类型', trigger: 'change' },
      ],
      username: [
        { required: true, message: '请填写用户名', trigger: 'blur' }
      ],
      money: [
        { type: 'number', required: true, message: '请填写金额', trigger: 'blur' }
      ],
      timeout: [
        { type: 'date', required: true, message: '请选择时间', trigger: 'change' }
      ],
      reward_name: [
        { type: 'string', required: true, message: '红包说明不能为空', trigger: 'blur' }
      ],
      money_limit: [
        { type: 'number',required: true, message: '红包限额不能为空', trigger: 'blur' }
      ],
      borrow_days: [
        {type: 'number', required: true, message: '投资期限不能为空', trigger: 'blur' }
      ]
    }
  }
  return data
}

//批量导出发放红包//批量导出新增年华券
export function sendRewardBatchData(){
  var data={
    actionUrl:'',
    title:'',
    type:'',
    pNum:0,//人数
    money:0,//金额
    invest_count:0,//投资金额
    achive_count:0,//业绩总额
    activeName: '1',
    curTitle:'全部红包',//默认头部的title
    activeName: 'first',
    activeNames:['1'],
    disable:true,//按钮的禁用状态标志
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    counts:'',
    searchForm: {
        name:''
     },
     tableData: {
          loading: false,
          head: [
             {key:'username',name:'客户账户'},
             {key:'money',name:'红包'},
             {key:'timeout',name:'过期时间'},
             {key:'timeout_unix',name:'过期时间（UNIX）'},
             {key:'use_together',name:'红包类型'},
             {key:'money_limit',name:'	红包限额'},
             {key:'borrow_days',name:'投资期限'},
             {key:'reward_name',name:'红包说明'},
             {key:'msg',name:'说明'},
          ],
          body: []
    },
  }

  return data
}

//充值列表
export function rechargeList(){
  var data={
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    sum:'',//汇总
    searchForm: {
      size:10,//每页多少条
      page:1,//当前页
      total:0,//总共多少条
      trade_no:'',
      username:'',
      realname:'',
      money:'',
      bank:'',
      recharge_time_begin:'',
      recharge_time_end:'',
      status:'',
      pay_type:'',
      activeTime:[],//搜索的开始时间和结束时间{F:线下充值,M:手工返现,O:还款充值,R:其他
      sts:[{type:1,name:'已通过'},{	type:2,name:'失败'},{type:3,name:'待审'}],
      payTypes:[{type:'F',name:'线下充值'},{type:'M',name:'手工返现'},{type:'O',name:'还款充值'},{type:'R',name:'其他'	}],
     },
     tableData: {
          loading: true,
          head: [
             {key:'id',name:'id'},
             {key:'trade_no',name:'订单编号'},
             {key:'username',name:'用户名'},
             {key:'realname',name:'姓名'},
             {key:'status',name:'类型'},
             {key:'bank_name',name:'所属银行'},
             {key:'money',name:'充值金额'},
             {key:'fee',name:'费用'},
             {key:'remark',name:'备注'},
             {key:'addtime',name:'添加时间'},
          ],
          body: []
         },
        modelTitle:'添加常量类型',
        isModelShow:false,
        ruleForm: {
            username: '',
            realname:'',
            info_name: '',
            pay_type: '',
            payTypes:[{type:'F',name:'线下充值'},{type:'M',name:'手工返现'},{type:'O',name:'还款充值'},{type:'R',name:'其他'	}],
            money:'',
            remark:'',
        },
        rules: {
          username: [ { required: true, message: 'name 必须填写.', trigger: 'blur' }],
          pay_type: [ { required: true, message: '类型必须选', trigger: 'change' }],
          money: [ { required: true, message: '金额 必须填写', trigger: 'blur' } ]
       }
  }
  return data
}

//资金账户列表
export function accountList(){
  var data={
    modelTitle:'添加系统参数',
    searchForm: {
      size:10,//每页多少条
      page:1,//当前页
      total:0,//总共多少条
      username:'',
      realname:'',
      money:'',
      condition:'',
      sts:[{type:1,name:'可用大于'},{	type:2,name:'可用小于'},{type:3,name:'冻结大于'},{type:4,name:'冻结小于'},{type:5,name:'待收大于'},{type:6,name:'待收小于'},{type:7,name:'总额大于'},{type:8,name:'总额小于'}],
     },
     tableData: {
      loading: true,
      head: [
         {key:'id',name:'id'},
         {key:'username',name:'用户名'},
         {key:'realname',name:'姓名'},
         {key:'total',name:'总额'},
         {key:'use_money',name:'可用'},
         {key:'no_use_money',name:'冻结'},
         {key:'wait_money',name:'待收'},
         {key:'wait_capital',name:'待收本金'},
         {key:'wait_interest',name:'待收利息'},
         {key:'fund',name:'网银充值'},
         {key:'taste_money',name:'taste_money'},
         {key:'xutou',name:'续投资金'},
      ],
      body: []
     }
  }
  return data
}

//资金账户月账单
export function getRecordCount(){
   var data ={
     id:'',
     max:'',
     cur:'',
     searchForm: {
       total:0,
       page:1,
       size:10,
      },
      tableAcountData: {
           loading: true,
           head: [
              {key:'id',name:'id'},
              {key:'user_id',name:'user_id'},
              {key:'total',name:'	总额'},
              {key:'use_money',name:'可用'},
              {key:'no_use_money',name:'冻结'},
              {key:'wait_capital',name:'待收本金'},
              {key:'wait_interest',name:'待收利息'},
              {key:'total_recharge',name:'	网银充值'},
              {key:'taste_money',name:'taste_money'},
              {key:'xutou',name:'	xutou'},
           ],
           body: []
      },
      tableData: {
           loading: true,
           head: [
              {key:'month',name:'月份'},
              {key:'recharge',name:'充值'},
              {key:'tender',name:'	提现'},
              {key:'repayment_receive',name:'投资'},
              {key:'tender_receive',name:'收益'},
              {key:'wait_interest',name:'借款'},
              {key:'repayment',name:'	还款'},
           ],
           body: []
      },
      tableData1: {
           loading: true,
           head: [
              {key:'title',name:'计算公式'},
              {key:'max',name:'历史最高充值金额'},
              {key:'cur',name:'	当前充值金额'},
           ],
           body: []
      },
   }
 return data
}

//年化券列表
export function userCouponList(){
  var data={
    pNum:0,//人数
    money:0,//金额
    invest_count:0,//投资金额
    achive_count:0,//业绩总额
    activeName: '1',
    curTitle:'全部红包',//默认头部的title
    activeName: 'first',
    activeNames:['1'],
    disable:true,//按钮的禁用状态标志
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    counts:'',

    searchForm: {
          size:10,//每页多少条
          page:1,//当前页
          total:0,//总共多少条
          user_id:'',
          username:'',//手机号
          coupon:'',//年华券
          is_use:'',//全部状态
          status:[
            {type:'',val:'全部状态'},
            {type:'1',val:'未使用'},
            {type:'2',val:'已使用'},
            {type:'3',val:'已过期'},
            {type:'4',val:'冻结'},
          ],
          get_time_begin:'',
          get_time_end:'',
          use_time_begin:'',
          use_time_end:'',
          borrow_id:'',//标ID
          tender_id:'',//投资ID
          borrow_type:'',//类型
          types:[],//所有的标类型
          coupon_name:'',//快速检索来源
          sources:[],
          setTime:[],
          useTime:[],
     },
     tableData: {
          loading: true,
          head: [
             {key:'user_id',name:'用户ID'},
             {key:'username',name:'手机号'},
              {key:'invite_userid',name:'渠道ID'},
             {key:'regtime',name:'注册时间'},
             {key:'coupon',name:'年化率'},
             {key:'coupon_name',name:'年化券名称'},
             {key:'money_limit',name:'投资限额'},
             {key:'borrow_days',name:'天数限额'},
             {key:'is_use',name:'使用状态'},
             {key:'timeout',name:'过期时间'},
             {key:'addtime',name:'获取时间'},
             {key:'usedtime',name:'使用时间'},
             {key:'tender_money',name:'投资金额'},
             {key:'performance',name:'业绩'},
             {key:'borrow_dayss',name:'收益期限'},
             {key:'coupon_capital',name:'贴息成本'},
             {key:'tender_id',name:'投资ID'},
             {key:'borrow_id',name:'标ID'},
              {key:'borrow_type',name:'标类型'},
              {key:'borrow_limit',name:'限定投资类型'},
          ],
          body: []
    },
  }

  return data
}

//发送给红包
export function addUserCouponBatch(){
  var data={
    pickerOptions2:{
      disabledDate(time) {
		     return time.getTime() <= (new Date().getTime());
		  }
    },
    ruleForm: {
      username:'',
      coupon_name:'',//年华券名称
      coupon:'',//年化率
      timeout:'',//过期时间
      money_minimun_limit:0,//起投金额
      money_limit:'',//最大限额
      borrow_days:'',//投资期限
      borrow_limit:[],//限定投资类型
      types:[],
    },
    rules: {
      coupon_name: [
        { required: true, message: '年华券名称', trigger: 'change' },
      ],
      username: [
        { required: true, message: '请填写用户名', trigger: 'blur' }
      ],
      coupon: [
        { required: true, message: '年化率', trigger: 'blur' }
      ],
      timeout: [
        { type: 'date', required: true, message: '请选择时间', trigger: 'change' }
      ],
      money_minimun_limit: [
        { type: 'number', required: true, message: '起投金额', trigger: 'blur' }
      ],
      money_limit: [
        { type: 'number',required: true, message: '最大限额', trigger: 'blur' }
      ],
      borrow_days: [
        {type: 'number', required: true, message: '投资期限', trigger: 'blur' }
      ],
      borrow_limit: [
        {required: true, message: '限定投资类型', trigger: 'change' }
      ]
    }
  }
  return data
}

//年化券列表
export function projectCount(){
  var data={
    month:'',//选择月份进行刷新
    pNum:0,//人数
    money:0,//金额
    invest_count:0,//投资金额
    achive_count:0,//业绩总额
    activeName: '1',
    curTitle:'全部红包',//默认头部的title
    activeName: 'first',
    activeNames:['1'],
    disable:true,//按钮的禁用状态标志
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    counts:'',

    searchForm: {
          size:10,//每页多少条
          page:1,//当前页
          total:0,//总共多少条
          pattern:'',//统计
          patterns:[
            {type:'1',val:'--按自然日统计--'},
            {type:'2',val:'--按自然周统计--'},
            {type:'3',val:'--按自然月统计--'},
            {type:'4',val:'--按自然季统计--'},
            {type:'5',val:'--按自然年统计--'},
          ],
          setTime:[],
          time_begin:'',
          time_end:'',
     },
     tableData: {
          loading: true,
          head: [
             {key:'s_date',name:'日期'},
             {key:'registered_users',name:'注册量'},
              {key:'verified_users',name:'实名认证人数'},
             {key:'new_investors',name:'新增投资人'},
             {key:'investment_users',name:'投资人数'},
             {key:'reward_money',name:'现金券金额'},
             {key:'cash_coupon',name:'红包券金额'},
             {key:'coupon_money',name:'年化券金额'},
             {key:'investment_money',name:'投资额'},
             {key:'performance',name:'业绩'},
             {key:'recharge_users',name:'充值人数'},
             {key:'recharge_money',name:'充值额'},
             {key:'cash_users',name:'提现人数'},
             {key:'cash_money',name:'提现金额'},
             {key:'repayment_users',name:'还款人数'},
             {key:'repayment_interest',name:'待还款利息'},
             {key:'repayment_money',name:'还款额'},
             {key:'new_borrowings',name:'新增借款金额'},
              {key:'investment_money',name:'	ROI'},
          ],
          body: []
    },
    tableData1: {
         loading: true,
         head: [
            {key:'user_id',name:'汇总'},
            {key:'username',name:'注册量'},
             {key:'invite_userid',name:'实名认证人数'},
            {key:'regtime',name:'新增投资人'},
            {key:'coupon',name:'渠道新增投资人'},
            {key:'coupon_name',name:'投资人数'},
            {key:'money_limit',name:'现金券金额'},
            {key:'borrow_days',name:'红包券金额'},
            {key:'is_use',name:'年化券金额'},
            {key:'timeout',name:'投资额'},
            {key:'addtime',name:'业绩'},
            {key:'usedtime',name:'充值人数'},
            {key:'tender_money',name:'充值额'},
            {key:'performance',name:'提现人数'},
            {key:'borrow_dayss',name:'提现金额'},
            {key:'coupon_capital',name:'还款人数'},
            {key:'tender_id',name:'待还款利息	'},
            {key:'borrow_id',name:'还款额'},
            {key:'borrow_type',name:'新增借款金额'},
            {key:'borrow_type',name:'ROI'},
         ],
         body: []
   },
  }

  return data
}

//红包日报表
export function rewardReport(){
  var data={
    curTitle:'红包日报表',//默认头部的title
    searchForm: {
          size:10,//每页多少条
          page:1,//当前页
          setTime:[],
          time_begin:'',
          time_end:'',
     },
     tableData: {
          loading: true,
          head: [
             {key:'riqi',name:'日期'},
             {key:'total',name:'发放红包总额	'},
              {key:'used_reward',name:'已使用红包	'},
             {key:'unused_reward',name:'未使用红包	'},
             {key:'timeout_reward',name:'当日已过期红包'},
          ],
          body: []
    }
  }

  return data
}

//投资列表
export function investList(){
  var data={
    curTitle:'红包日报表',//默认头部的title
    searchForm: {
          username:'',
          money:'',
          invite_userid:'',
          name:'',
          setTime:[],
          time_begin:'',
          time_end:'',
     },
     tableData: {
          loading: true,
          head: [
             {key:'user_id',name:'id'},
             {key:'username',name:'用户名	'},
             {key:'realname',name:'	真实名称	'},
             {key:'money',name:'	投资金额	'},
             {key:'reward',name:'红包使用'},
             {key:'coupon',name:'年化劵信息	'},
             {key:'name',name:'	投标名称	'},
             {key:'apr',name:'	标的年化	'},
             {key:'cyc_time',name:'	标的周期'},
             {key:'end_time',name:'	到期时间	'},
             {key:'addtime',name:'投资时间'},
             {key:'invite_userid',name:'	渠道id'},
             {key:'invite_name',name:'渠道用户名'},
             {key:'app_marketing',name:'推广标识'},
          ],
          body: []
    }
  }

  return data
}

//贷款资金
export function loanList(){
  var data={
    curTitle:'贷款资金',//默认头部的title
    searchForm: {
          size:10,//每页多少条
          page:1,//当前页
          total:0,//总共多少条
          apr:'',//利率
          borrow_type:'',//表类型
          real_status:'',//全部状态,
          success_time_begin:'',
          success_time_end:'',//开标时间,
          repay_time_begin:'',//还款时间,
          repay_time_end:'',
          status:[
            {type:'0',val:'全部状态'},
            {type:'-1',val:'等待审核'},
            {type:'1',val:'审核失败'},
            {type:'2',val:'投资中'},
            {type:'3',val:'已满标'},
            {type:'4',val:'已流标'},
            {type:'5',val:'还款中'},
            {type:'6',val:'已还款'},
            {type:'7',val:'满标待审'},
          ],
          types:[],//所有的标类型
          setTime:[],
          useTime:[],
     },
     tableData: {
          loading: true,
          head: [
             {key:'id',name:'id'},
             {key:'realname',name:'	发标人'},
              {key:'name',name:'	标题'},
             {key:'success_time',name:'开标时间'},
             {key:'end_time',name:'还款日期'},
             {key:'repayment_time',name:'实际还款时间'},
             {key:'account',name:'	总金额'},
             {key:'performance',name:'	业绩'},
             {key:'apr',name:'	年化'},
             {key:'reward',name:'使用红包'},
             {key:'interest',name:'利息'},
             {key:'coupon_money',name:'	贴息金额'},
             {key:'borrow_type',name:'	标类型'},
             {key:'real_state',name:'状态'},
          ],
          body: []
    },
  }

  return data
}

//充值资金
export function rechargeCount(){
  var data={
    curTitle:'红包日报表',//默认头部的title
    searchForm: {
          setTime:[],
          time_begin:'',
          time_end:'',
     },
     tableData: {
          loading: true,
          head: [
             {key:'dt',name:'日期'},
             {key:'moneys',name:'用户充值A	'},
             {key:'pv',name:'次数'},
             {key:'rpmoneys',name:'流水金额B'},
             {key:'rppv',name:'次数'},
             {key:'camoneys',name:'借款提现'},
             {key:'capv',name:'	次数	'},
             {key:'brmoneys',name:'	借款充值		'},
             {key:'tbrmoneys',name:'	次数'},
          ],
          body: []
    }
  }

  return data
}

//资金日报
export function moneyReport(){
  var data={
    curTitle:'资金日报',//默认头部的title
    searchForm: {
          setTime:[],
          time_begin:'',
          time_end:'',
     },
     tableData: {
          loading: true,
          head: [
             {key:'dt',name:'日期'},
             {key:'moneys',name:'投资额'},
             {key:'performance',name:'业绩	'},
             {key:'rewards',name:'使用红包	'},
             {key:'pv',name:'投资次数	'},
             {key:'repayments',name:'还款额	'},
             {key:'interests',name:'	还款利息	'},
             {key:'pvh',name:'	还款标个数	'},
          ],
          body: []
    }
  }

  return data
}

//红包列表
export function overview(){
  var data={
    curTitle:'资金汇总',//默认头部的title
    activeNames:['1'],
    counts:'',
    resList:[
      {key:'no_use_moneys',name:'冻结'},
      {key:'recharges',name:'充值'},
      {key:'use_moneys',name:'可用'},
      {key:'wait_moneys',name:'待收'},
      {key:'withdrawals',name:'提现'},
      {key:'invests',name:'总投资'},
      {key:'use_rewards',name:'使用的红包'},
      {key:'Interests',name:'利息'},
      {key:'repays',name:'总还款'},
      {key:'no_use',name:'未用'},
      {key:'used',name:'已用'},
      {key:'sum',name:'全部'}
    ],//类型
  }
  return data
}


//提现列表
export function withdrawList(){
  var data={
    isModelShowSee:false,
    isModelShow:false,
    pNum:0,//人数
    money:0,//金额
    invest_count:0,//投资金额
    achive_count:0,//业绩总额
    activeName: '1',
    curTitle:'全部红包',//默认头部的title
    activeName: 'first',
    activeNames:['1'],
    disable:true,//按钮的禁用状态标志
    modelTitle:'添加系统参数',
    detail:false,//查看详情
    submitStatus:'add',//提交状态
    counts:'',
    searchForm: {
          size:10,//每页多少条
          page:1,//当前页
          total:0,//总共多少条
          username:'',
          realname:'',
          withdraw_no:'',
          type:'',//[>=,=,<=],
          types:[
            {type:'=',val:'等于'},
            {type:'>=',val:'大于'},
            {type:'<=',val:'小于'},
          ],
          money:'',
          add_time_begin:'',
          add_time_end:'',
          verify_time_begin:'',
          verify_time_end:'',
          status:'',
          sina_st:'',//新浪状态
          statuss:[
            {type:'0',val:'全部状态'},
            {type:'1',val:'提现成功'},
            {type:'2',val:'提现失败'},
            {type:'3',val:'您已取消'},
            {type:'4',val:'银行处理中'},
            {type:'-1',val:'待审核'},
          ],
          sina_sts:[
            {type:'',val:'新浪状态'},
            {type:'cash_default',val:'默认'},
            {type:'cash_unfreeze',val:'解冻'},
            {type:'SUCCESS',val:'成功'},
            {type:'FAILED',val:'失败'},
            {type:'PROCESSING',val:'银行处理中'},
          ],
          setTime:[],
          useTime:[],
     },
     tableData: {
          loading: true,
          head: [
             {key:'id',name:'id'},
             {key:'username',name:'用户名'},
             {key:'realname',name:'姓名'},
             {key:'account',name:'卡号'},
             {key:'bank',name:'银行'},
             {key:'branch',name:'支行'},
             {key:'total',name:'	提现总额'},
             {key:'addtime',name:'	提现时间'},
             {key:'verify_time',name:'审核时间'},
             {key:'status',name:'状态'},
          ],
          body: []
    },
    ruleForm: {
        id:'',
        status:'',
        money:'',//到账金额
        verify_remark:'',//审核备注
    },
    rules: {
      status: [ { required: true, message: '请选择状态', trigger: 'change' }],
      verify_remark: [ { required: true, message: '审核备注', trigger: 'blur' }],
   },
   ruleFormSee:[
     {key:'username',name:'用户名',val:''},
      {key:'realname',name:'姓名',val:''},
      {key:'bank',name:'提现银行',val:''},
      {key:'city_name',name:'省市区',val:''},
      {key:'branch',name:'提现支行',val:''},
      {key:'account',name:'提现账号',val:''},
      {key:'total',name:'提现总额',val:''},
      {key:'status',name:'状态',val:''},
      {key:'addtimeOrIp',name:'添加时间/IP',val:''},
   ]
  }

  return data
}


//借款列表
export function borrowList(){
  var data={
    param : new FormData(),
    dialogImageUrl: '',
    dialogVisible: false,
    editorOption: {
        placeholder: 'Hello World'
    },
    modelTitle:'添加常量类型',
    isModelShow:false,
    isModelShowSee:false,
    disable:true,//按钮的禁用状态标志
    multipleSelection: [], //被选中的删除项
    modelTitle:'添加系统参数',
    submitStatus:'add',//提交状态
    searchForm: {
      size:10,//每页多少条
      page:1,//当前页
      total:0,//总共多少条
      username:'',
      name:'',//标题
      real_state:'',//状态
      money_begin:'',//借款金额起
      money_end:'',//借款金额止
      time_limit_begin:'',
      time_limit_end:'',
      apr:'',//利率
      sts:[
        {type:'-1',name:'等待审核'},
        {	type:'1',name:'审核失败'},
        {type:'2',name:'投资中'},
        {type:'3',name:'已满标'},
        {type:'4',name:'已流标'},
        {type:'5',name:'还款中'},
        {type:'6',name:'已还款'},
        {type:'7',name:'满标待审'},
      ],
     },
     tableData: {
          loading: true,
          head: [
             {key:'id',name:'ID'},
             {key:'username',name:'发标人'},
             {key:'realname',name:'真实姓名'},
             {key:'name',name:'	借款标题'},
             {key:'account',name:'	借款金额'},
             {key:'account_yes',name:'已投金额'},
             {key:'apr',name:'利率'},
             {key:'time',name:'合同时间'},
             {key:'success_time',name:'开标时间'},
             {key:'time_limit_day',name:'	借款期限'},
             {key:'borrow_type',name:'	借款类型'},
             {key:'new_hand',name:'标类型'},
             {key:'real_state',name:'状态'},
          ],
          body: []
         },
         ruleForm: {
          imgList: [],//显示的图片
          files:[],//上传的图片
          username:'',//发标人
          realname:'',
          borrow_type:244,//借款类型
          borrow_types:[
            {type:'244',name:'配资贷'},
            {type:'245',name:'票据贷 '},
            {type:'246',name:'车抵押  '},
            {type:'247',name:'企业过桥  '},
            {type:'248',name:'垫资贷  '},
            {type:'508',name:'流资贷 '},
            {type:'509',name:'活动体验标 '},
            {type:'827',name:'房抵贷 '},
            {type:'828',name:'信用贷'},
            {type:'830',name:'聚财宝'}
          ],
          new_hand:'',//标类型
          new_hands:[
            {type:'0',name:'普通标  '},
            {type:'1',name:'vip标  '},
            {type:'2',name:'新手标   '},
          ],
          style:'',//还款方式
          styles:[
            {type:'1',name:'到期一次性还本付息  '},
            {type:'2',name:'按日计息,按月付息,到期还本 '},
          ],
          account:'',//借款总金额
          use:'',//借款用途
          original_rate:'',//原始利率
          discount_rate:'',//贴息利率
          lowest_account:'',//最低投标金额
          lowest_accounts:[
            {type:'10',name:'10元'},
            {type:'100',name:'10元'},
            {type:'1000',name:'10元'},
            {type:'5000',name:'5000元'},
            {type:'10000',name:'10000元'}
          ],
          most_account:'',//最多投标金额
          most_accounts:[
            {type:'0',name:'没有限制'},
            {type:'1000',name:'10元'},
            {type:'1500',name:'10元'},
            {type:'3000',name:'3000元'},
            {type:'5000',name:'5000元'},
            {type:'10000',name:'10000元'},
            {type:'20000',name:'20000元'},
            {type:'30000',name:'30000元'},
            {type:'50000',name:'50000元'},
          ],
          tender_user:'',//专投手机号
          invite_user:'',//专投渠道号
          contract_no:'',//原合同编号
          pawn:'',//债劵抵(质)押信息
          litpic:'',
          litpics:[],//缩略图
          start_time:'',//标投放开始时间
          time_limit_day:'',//标期
          id:'',
          name:'',//借款标题
          sign:'',//标记
          content:'',//内容描述
       },
       ruleFormInit:{},//用来存放表单的初始值
       rules: {
         username: [
           { required: true, message: '发标人', trigger: 'blur' },
         ],
         borrow_type: [
           { required: true, message: '请选择借款类型', trigger: 'change' }
         ],
         new_hand: [
           {  required: true, message: '标类型', trigger: 'change' }
         ],
         style: [
           { required: true, message: '还款方式', trigger: 'change' }
         ],
         account: [
           { required: true, message: '借款总金额', trigger: 'blur' }
         ],
         use: [
           {required: true, message: '借款用途', trigger: 'blur' }
         ],
         original_rate: [
           { required: true, message: '原始利率', trigger: 'blur' }
         ],
         discount_rate: [
           { required: false, message: '贴息利率', trigger: 'blur' }
         ],
         lowest_account: [
           { required: true, message: '最低投标金额', trigger: 'change' }
         ],
         most_account: [
           { required: true, message: '最多投标总额', trigger: 'change' }
         ],
         tender_user: [
           { required: true, message: '专投手机号', trigger: 'blur' }
         ],
         invite_user: [
           { required: true, message: '专投渠道号', trigger: 'blur' }
         ],
         contract_no: [
           { required: true, message: '原合同编号', trigger: 'blur' }
         ],
         pawn: [
           { required: true, message: '债劵抵(质)押信息', trigger: 'blur' }
         ],
         litpic: [
           { required: false, message: '缩略图', trigger: 'blur' }
         ],
         start_time: [
           { required: true, message: '标投放开始时间', trigger: 'change' }
         ],
         time_limit_day: [
           { required: true, message: '标期', trigger: 'blur' }
         ],
         name: [
           { required: true, message: '借款标题', trigger: 'blur' }
         ],
         sign: [
           { required: true, message: '标记', trigger: 'blur' }
         ],
         content: [
           { required: true, message: '内容描述', trigger: 'blur' }
         ]
       },
       ruleFormList:[
          {key:'username',name:'用户名',val:''},
          {key:'use',name:'状态',val:''},//?
          {key:'use',name:'借款用途',val:''},
          {key:'new_hand',name:'标类型',val:''},
          {key:'style',name:'还款方式',val:''},
          {key:'account',name:'借贷总金额',val:''},
          {key:'original_rate',name:'年利率',val:''},
          {key:'lowest_account',name:'最低投标金额',val:''},
          {key:'most_account',name:'最多投标总额',val:''},
          {key:'name',name:'标题',val:''},
          {key:'tender_user',name:'专投手机号',val:''},
          {key:'invite_user',name:'专投渠道号',val:''},
          {key:'contract_no',name:'原合同编号',val:''},
          {key:'pawn',name:'债券抵(质)押物信息',val:''},
          {key:'litpics',name:'缩略图',val:''},
          {key:'addtimeOrIp',name:'添加时间/IP',val:''},
       ],
       ruleFormSee:{
         id:'',
         real_state:2,//状态
         recomend_flg:1,//推荐标
         success_time:'',//发布时间
         verify_remark:''//审核备注
       },
       rulesSee:{
         real_state: [
           { required: true, message: '状态', trigger: 'change' },
         ],
         recomend_flg: [
           { required: true, message: '推荐标', trigger: 'change' }
         ],
         success_time: [
           {  required: true, message: '发布时间', trigger: 'change' }
         ],
         verify_remark: [
           { required: true, message: '审核备注', trigger: 'blur' }
         ]
       }
  }

  return data
}


//还款计划
export function repayList(){
  var data ={
    modelTitle:'',
    searchForm: {
          size:10,//每页多少条
          page:1,//当前页
          total:0,//总共多少条
          username:'',
          name:'',
          status:'',
          sts:[
            {type:'0',name:'全部状态'},
            {type:'1',name:'已还款'},
            {type:'-1',name:'未还款'},
          ]
     },
     tableData: {
          loading: true,
          head: [
             {key:'id',name:'标ID'},
             {key:'username',name:'	发标人'},
             {key:'realname',name:'	实名'},
             {key:'end_time',name:'还款日期'},
             {key:'capital',name:'本金'},
             {key:'interest',name:'	利息'},
             {key:'repayment_account',name:'还款金额'},
             {key:'account',name:'项目金额'},
             {key:'name',name:'	借款标题'},
             {key:'apr',name:'	利率'},
             {key:'time',name:'	合同时间'},
             {key:'time_limit_day',name:'借款期限'},
             {key:'style',name:'借款类型'},
             {key:'status',name:'状态'},
          ],
          body: []
     },
  }
  return data
}
