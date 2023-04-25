import React, {Suspense} from 'react'
import {Navigate, Route, Routes} from 'react-router-dom'
import {CContainer, CSpinner} from '@coreui/react'

const CampusListPage = React.lazy(() => import('./../pages/campus/CampusListPage'))
const CampusAddPage = React.lazy(() => import('./../pages/campus/CampusAddPage'))
const CampusEditPage = React.lazy(() => import('./../pages/campus/CampusEditPage'))
const CourseListPage = React.lazy(() => import('./../pages/course/CourseListPage'))
const CourseAddPage = React.lazy(() => import('./../pages/course/CourseAddPage'))
const CourseEditPage = React.lazy(() => import('./../pages/course/CourseEditPage'))

const AppContent = () => {
  const urls = window.abeApp.urls
  return (
    <CContainer lg>
      <Suspense fallback={<CSpinner color="primary"/>}>
        <Routes>
          <Route exact path={urls.school_course_list_show} name="Courses" element={<CourseListPage/>}/>
          <Route exact path={urls.school_course_add} name="Add new course" element={<CourseAddPage/>}/>
          <Route exact path={urls.school_course_edit} name="Edit course" element={<CourseEditPage/>}/>
          <Route exact path={urls.school_campus_list_show} name="Campuses" element={<CampusListPage/>}/>
          <Route exact path={urls.school_campus_add} name="Add new campus" element={<CampusAddPage/>}/>
          <Route exact path={urls.school_campus_edit} name="Edit campus" element={<CampusEditPage/>}/>
          <Route exact path={urls.school_home} name="Home" element={<div>dashboard</div>}/>
          <Route path="/" element={<Navigate to="dashboard" replace/>}/>
        </Routes>
      </Suspense>
    </CContainer>
  )
}

export default React.memo(AppContent)
