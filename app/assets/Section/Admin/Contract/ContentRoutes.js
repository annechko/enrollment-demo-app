import React from 'react'
import {
  Navigate,
  Route
} from 'react-router-dom'
import DefaultLayout from '../../../App/Layout'

const AdminLoginPage = React.lazy(async () => await import('../Auth/LoginPage'))
const SchoolList = React.lazy(async () => await import('../School/SchoolList'))
const HomeDashboard = React.lazy(async () => await import('../Dashboard/HomeDashboard'))

const urls = window.abeApp.urls
let key = 0

export default [
  <Route key={key++} exact path={window.abeApp.urls.admin_login} element={<AdminLoginPage/>}/>,

  <Route key={key++} element={<DefaultLayout/>}>
    <Route exact path={urls.admin_home} element={<HomeDashboard/>}/>
    <Route exact path={urls.admin_school_list_show} element={<SchoolList/>}/>
    <Route exact path="/admin/*" element={<Navigate to={urls.admin_home} replace/>}/>
  </Route>
]
