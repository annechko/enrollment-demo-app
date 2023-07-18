import React, {Suspense} from 'react'
import {
  AppFooter,
  AppHeader,
  AppSidebar
} from '../Common'
import {
  CContainer,
  CSpinner
} from "@coreui/react";
import {Outlet} from "react-router-dom";

const DefaultLayout = () => {

  return (
    <div>
      <AppSidebar/>
      <div className="wrapper d-flex flex-column min-vh-100 bg-light">
        <AppHeader/>
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
