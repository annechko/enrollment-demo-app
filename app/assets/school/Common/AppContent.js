import {
  CContainer,
  CSpinner
} from '@coreui/react'
import React, {Suspense} from 'react'
import {
  Navigate,
  Route,
  Routes
} from 'react-router-dom'

const CampusListPage = React.lazy(() => import('../Pages/Campus/List/CampusListPage'))
const CampusAddPage = React.lazy(() => import('../Pages/Campus/Add/CampusAddPage'))
const CampusEditPage = React.lazy(() => import('../Pages/Campus/Edit/CampusEditPage'))
const CourseListPage = React.lazy(() => import('../Pages/Course/List/CourseListPage'))
const CourseAddPage = React.lazy(() => import('../Pages/Course/Add/CourseAddPage'))
const CourseEditPage = React.lazy(() => import('../Pages/Course/Edit/CourseEditPage'))

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
