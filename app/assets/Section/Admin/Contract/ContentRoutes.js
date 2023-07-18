import React from 'react'
import {
  Navigate,
  Route
} from "react-router-dom";
import DefaultLayout from "../../../App/Layout";

const AdminLoginPage = React.lazy(() => import('../Auth/LoginPage'))

const urls = window.abeApp.urls
let key = 0

export default [
  <Route key={key++} exact path={window.abeApp.urls.admin_login} element={<AdminLoginPage/>}/>,

  <Route key={key++} element={<DefaultLayout/>}>
    <Route exact path={urls.admin_home} element={<div> admin_home</div>}/>
    <Route exact path="/admin/*" element={<Navigate to={urls.admin_home} replace/>}/>
  </Route>
]

