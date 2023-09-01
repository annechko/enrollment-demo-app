import React, {
  Suspense,
  useState
} from 'react'
import {
  AppFooter,
  AppHeader,
  AppSidebar
} from '../Common'
import {
  CContainer,
  CSpinner
} from '@coreui/react'
import { Outlet } from 'react-router-dom'

const DefaultLayout = () => {
  const [isSidebarVisible, setIsSidebarVisible] = useState(true)
  const toggleSidebarVisible = () => {
    setIsSidebarVisible(!isSidebarVisible)
  }

  return (
    <div data-test-id="default-layout">
      <AppSidebar isSidebarVisible={isSidebarVisible} setIsSidebarVisible={setIsSidebarVisible}/>
      <div className="wrapper d-flex flex-column min-vh-100 bg-light">
        <AppHeader toggleSidebarVisible={toggleSidebarVisible}/>
        <div className="body flex-grow-1 px-3">
          <CContainer lg>
            <Suspense fallback={<CSpinner color="primary"/>}>
              <Outlet/>
            </Suspense>
          </CContainer>
        </div>
        <AppFooter/>
      </div>
    </div>
  )
}

export default DefaultLayout
