import {
  CContainer,
  CSpinner
} from '@coreui/react'
import React, {Suspense} from 'react'

const SchoolRoutes = React.lazy(() => import('../../Section/School/Routes'))
const AdminRoutes = React.lazy(() => import('../../Section/Admin/Routes'))

const AppContent = () => {
  const currentSection = window.abeApp.currentSection
  const routes = currentSection === 'school'
    ? <SchoolRoutes/>
    : (currentSection === 'admin' ? <AdminRoutes/> : <AdminRoutes/>)

  return (
    <CContainer lg>
      <Suspense fallback={<CSpinner color="primary"/>}>
        {routes}
      </Suspense>
    </CContainer>
  )
}

export default React.memo(AppContent)
