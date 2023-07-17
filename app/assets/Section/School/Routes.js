import React from 'react'
import {
  Route,
  Routes
} from "react-router-dom";

const CampusListPage = React.lazy(() => import('../../school/Pages/Campus/List/CampusListPage'))
const CampusAddPage = React.lazy(() => import('../../school/Pages/Campus/Add/CampusAddPage'))
const CampusEditPage = React.lazy(() => import('../../school/Pages/Campus/Edit/CampusEditPage'))
const CourseListPage = React.lazy(() => import('../../school/Pages/Course/List/CourseListPage'))
const CourseAddPage = React.lazy(() => import('../../school/Pages/Course/Add/CourseAddPage'))
const CourseEditPage = React.lazy(() => import('../../school/Pages/Course/Edit/CourseEditPage'))
const urls = window.abeApp.urls

const SchoolRoutes = () => {
  return (<Routes>
      <Route exact path={urls.school_course_list_show} name="Courses" element={<CourseListPage/>}/>
      <Route exact path={urls.school_course_add} name="Add new course" element={<CourseAddPage/>}/>
      <Route exact path={urls.school_course_edit} name="Edit course" element={<CourseEditPage/>}/>
      <Route exact path={urls.school_campus_list_show} name="Campuses" element={<CampusListPage/>}/>
      <Route exact path={urls.school_campus_add} name="Add new campus" element={<CampusAddPage/>}/>
      <Route exact path={urls.school_campus_edit} name="Edit campus" element={<CampusEditPage/>}/>
      <Route exact path={urls.school_home} name="Home" element={<div>school_home</div>}/>
      <Route exact path={urls.school_student_list_show} name="Home" element={
        <div>students will be here</div>}/>
      {/* todo default route to home <Route path="/" element={<Navigate to="dashboard" replace/>}/>*/}
    </Routes>
  )
}
export default SchoolRoutes
