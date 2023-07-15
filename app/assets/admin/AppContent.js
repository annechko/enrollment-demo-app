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

const AppContent = () => {
  const urls = window.abeApp.urls
  return (
    <CContainer lg>
      <Suspense fallback={<CSpinner color="primary"/>}>
        <Routes>
          <Route exact path={urls.admin_school_list} name="Home" element={<div>admin admin_school_list</div>}/>
          <Route exact path={urls.admin_home} name="Home" element={<div> admin_home</div>}/>
          <Route path="/" element={<Navigate to="dashboard" replace/>}/>
        </Routes>
      </Suspense>
    </CContainer>
  )
}

export default React.memo(AppContent)
