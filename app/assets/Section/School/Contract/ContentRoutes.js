import React from 'react'
import {
  Navigate,
  Route
} from "react-router-dom";
import DefaultLayout from "../../../App/Layout";

const LoginPage = React.lazy(() => import('../Auth/LoginPage'))
const RegisterPage = React.lazy(() => import('../Auth/RegisterPage'))
const CampusListPage = React.lazy(() => import('../Campus/List/CampusListPage'))
const CampusAddPage = React.lazy(() => import('../Campus/Add/CampusAddPage'))
const CampusEditPage = React.lazy(() => import('../Campus/Edit/CampusEditPage'))
const CourseListPage = React.lazy(() => import('../Course/List/CourseListPage'))
const CourseAddPage = React.lazy(() => import('../Course/Add/CourseAddPage'))
const CourseEditPage = React.lazy(() => import('../Course/Edit/CourseEditPage'))
const SchoolProfile = React.lazy(() => import('../Profile/SchoolProfile'))

const urls = window.abeApp.urls
let key = 0

export default [
  <Route key={key++} exact path={urls.school_login} element={<LoginPage urls={urls}/>}/>,
  <Route key={key++} exact path={urls.school_register} element={<RegisterPage urls={urls}/>}/>,

  <Route key={key++} element={<DefaultLayout/>}>
    <Route exact path={urls.school_course_list_show} element={<CourseListPage/>}/>,
    <Route exact path={urls.school_course_add} element={<CourseAddPage/>}/>,
    <Route exact path={urls.school_course_edit} element={<CourseEditPage/>}/>,
    <Route exact path={urls.school_campus_list_show} element={<CampusListPage/>}/>,
    <Route exact path={urls.school_campus_add} element={<CampusAddPage/>}/>,
    <Route exact path={urls.school_campus_edit} element={<CampusEditPage/>}/>,
    <Route exact path={urls.school_home} element={<div>school_home</div>}/>,
    <Route exact path={urls.school_profile_show} element={<SchoolProfile/>}/>,
    <Route exact path={urls.school_student_list_show} element={
      <div>students will be here</div>}/>,
    <Route exact path="/school/*" element={<Navigate to={urls.school_home} replace/>}/>
  </Route>
]

