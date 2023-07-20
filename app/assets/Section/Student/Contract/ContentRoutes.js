import React from 'react'
import {
  Navigate,
  Route
} from "react-router-dom";
import DefaultLayout from "../../../App/Layout";

const LoginPage = React.lazy(() => import('../Auth/LoginPage'))
const RegisterPage = React.lazy(() => import('../Auth/RegisterPage'))

const urls = window.abeApp.urls
let key = 0

export default [
  <Route key={key++} exact path={urls.student_login} element={<LoginPage />}/>,
  <Route key={key++} exact path={urls.student_register} element={<RegisterPage />}/>,

  <Route key={key++} element={<DefaultLayout/>}>
    <Route exact path={urls.student_home} element={<div>student_home</div>}/>,

    <Route exact path="/student/*" element={<Navigate to={urls.student_home} replace/>}/>
  </Route>
]

