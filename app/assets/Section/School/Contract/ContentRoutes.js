import React from 'react'
import {
  Navigate,
  Route
} from 'react-router-dom'
import DefaultLayout from '../../../App/Layout'

const LoginPage = React.lazy(async () => await import('../Auth/LoginPage'))
const RegisterPage = React.lazy(async () => await import('../Auth/RegisterPage'))
const CampusListPage = React.lazy(async () => await import('../Campus/List/CampusListPage'))
const CampusAddPage = React.lazy(async () => await import('../Campus/Add/CampusAddPage'))
const CampusEditPage = React.lazy(async () => await import('../Campus/Edit/CampusEditPage'))
const CourseListPage = React.lazy(async () => await import('../Course/List/CourseListPage'))
const CourseAddPage = React.lazy(async () => await import('../Course/Add/CourseAddPage'))
const CourseEditPage = React.lazy(async () => await import('../Course/Edit/CourseEditPage'))
const SchoolProfile = React.lazy(async () => await import('../Profile/SchoolProfile'))
const ApplicationList = React.lazy(async () => await import('../Application/ApplicationList'))
const ApplicationEdit = React.lazy(async () => await import('../Application/ApplicationEdit'))
const StaffMemberCompleteRegister = React.lazy(async () => await import('../Auth/StaffMemberCompleteRegister'))

const urls = window.abeApp.urls
let key = 0

export default [
  <Route key={key++} exact path={urls.school_login} element={<LoginPage urls={urls}/>}/>,
  <Route key={key++} exact path={urls.school_register} element={<RegisterPage urls={urls}/>}/>,
  <Route key={key++} exact path={urls.school_member_register} element={<StaffMemberCompleteRegister/>}/>,

  <Route key={key++} element={<DefaultLayout/>}>
    <Route exact path={urls.school_course_list} element={<CourseListPage/>}/>,
    <Route exact path={urls.school_course_add} element={<CourseAddPage/>}/>,
    <Route exact path={urls.school_course_edit} element={<CourseEditPage/>}/>,
    <Route exact path={urls.school_campus_list} element={<CampusListPage/>}/>,
    <Route exact path={urls.school_campus_add} element={<CampusAddPage/>}/>,
    <Route exact path={urls.school_campus_edit} element={<CampusEditPage/>}/>,
    <Route exact path={urls.school_home} element={<div>school_home</div>}/>,
    <Route exact path={urls.school_profile} element={<SchoolProfile/>}/>,
    <Route exact path={urls.school_application_list_show} element={<ApplicationList/>}/>,
    <Route exact path={urls.school_application_edit} element={<ApplicationEdit/>}/>,
    <Route exact path={urls.school_student_list_show} element={
      <div>students will be here</div>}/>,
    <Route exact path="/school/*" element={<Navigate to={urls.school_home} replace/>}/>
  </Route>
]
