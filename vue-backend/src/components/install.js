// 组件全局注册
import Vue from 'vue'

import AppNotes from './AppNotes'
import AppTitle from './AppTitle'
import AppToolbar from './AppToolbar'
import AppSection from './AppSection'
import AppSearch from './AppSearch'
import TableMixin from './TableMixin'
//import Confirm from './Confirm'

// 组件库
const Components = [
    AppNotes,
    AppTitle,
    AppToolbar,
    AppSection,
    AppSearch,
    TableMixin,
//  Confirm
]

// 注册全局组件
Components.map((com) =>{
    Vue.component(com.name, com)
})

export default Vue