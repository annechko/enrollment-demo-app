import React from 'react'
import {NavLink} from 'react-router-dom'
import {
  CContainer,
  CHeader,
  CHeaderBrand,
  CHeaderDivider,
  CHeaderNav,
  CHeaderToggler,
  CNavLink,
  CNavItem,
} from '@coreui/react'
import CIcon from '@coreui/icons-react'
import {cilBell, cilEnvelopeOpen, cilList, cilMenu} from '@coreui/icons'

// import { AppBreadcrumb } from './index'
import {AppHeaderDropdown} from './header/index'
// import { logo } from 'src/assets/brand/logo'

const AppHeader = () => {

  return (
    <CHeader position="sticky" className="mb-4">
      <CContainer fluid className="header-items-right">
        <CHeaderNav>
          <CNavItem>
            <CNavLink href="#">
              <CIcon icon={cilBell} size="lg"/>
            </CNavLink>
          </CNavItem>
          <CNavItem>
            <CNavLink href="#">
              <CIcon icon={cilList} size="lg"/>
            </CNavLink>
          </CNavItem>
          <CNavItem>
            <CNavLink href="#">
              <CIcon icon={cilEnvelopeOpen} size="lg"/>
            </CNavLink>
          </CNavItem>
        </CHeaderNav>
        <CHeaderNav className="ms-3">
          <AppHeaderDropdown/>
        </CHeaderNav>
      </CContainer>
      {/*<CHeaderDivider />*/}
      {/*<CContainer fluid>*/}
      {/*<AppBreadcrumb />*/}
      {/*</CContainer>*/}
    </CHeader>
  )
}

export default AppHeader
