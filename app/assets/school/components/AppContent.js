import React, {Suspense} from 'react'
import {Navigate, Route, Routes} from 'react-router-dom'
import {CContainer, CSpinner} from '@coreui/react'

const CampusListPage = React.lazy(() => import('./../pages/campus/CampusListPage'))
const CampusesAdd = React.lazy(() => import('./../views/campus/CampusesAdd'))
const CourseList = React.lazy(() => import('./../views/course/CourseList'))
const CourseAdd = React.lazy(() => import('./../views/course/CourseAdd'))
// const CampusesEdit = React.lazy(() => import('./../views/campus/CampusesEdit'))

const AppContent = () => {
  const urls = window.abeApp.urls
  return (
    <CContainer lg>
      <Suspense fallback={<CSpinner color="primary"/>}>
        <Routes>
          <Route exact path={urls.school_course_list_show} name="Courses" element={<CourseList/>}/>
          <Route exact path={urls.school_course_add} name="Add new course" element={<CourseAdd/>}/>
          <Route exact path={urls.school_campus_list_show} name="Campuses" element={<CampusListPage/>}/>
          <Route exact path={urls.school_campus_add} name="Add new campus" element={<CampusesAdd/>}/>
          <Route exact path={urls.school_campus_edit} name="Edit campus" element={<CampusesAdd/>}/>
          <Route exact path={urls.school_home} name="Home" element={<div>dashboard</div>}/>
          <Route path="/" element={<Navigate to="dashboard" replace/>}/>
        </Routes>
      </Suspense>
    </CContainer>
  )
}

export default React.memo(AppContent)
