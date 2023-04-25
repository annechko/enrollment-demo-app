import React, {Suspense} from 'react'
import {Navigate, Route, Routes} from 'react-router-dom'
import {CContainer, CSpinner} from '@coreui/react'

const Campuses = React.lazy(() => import('./../views/campus/Campuses'))
const CampusesAdd = React.lazy(() => import('./../views/campus/CampusesAdd'))
const CourseList = React.lazy(() => import('./../views/course/CourseList'))
const CourseAdd = React.lazy(() => import('./../views/course/CourseAdd'))
// const CampusesEdit = React.lazy(() => import('./../views/campus/CampusesEdit'))

const AppContent = () => {
  return (
    <CContainer lg>
      <Suspense fallback={<CSpinner color="primary"/>}>
        <Routes>
          <Route exact path={window.abeApp.urls.COURSES} name="Courses Page" element={
            <CourseList/>}/>
          <Route exact path={window.abeApp.urls.COURSE_ADD} name="Add new course" element={
            <CourseAdd/>}/>
          <Route exact path={window.abeApp.urls.CAMPUSES} name="Campuses Page" element={
            <Campuses/>}/>
          <Route exact path={window.abeApp.urls.CAMPUSES_ADD} name="Add new campus" element={
            <CampusesAdd/>}/>
          <Route exact path={window.abeApp.urls.CAMPUSES_EDIT} name="Edit campus" element={
            <CampusesAdd/>}/>
          <Route exact path="school/" name="Home" element={<div>dashboard</div>}/>
          <Route path="/" element={<Navigate to="dashboard" replace/>}/>
        </Routes>
      </Suspense>
    </CContainer>
  )
}

export default React.memo(AppContent)
