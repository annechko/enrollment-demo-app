import {
  cilBell,
  cilEnvelopeOpen,
  cilList
} from '@coreui/icons'
import CIcon from '@coreui/icons-react'
import {
  CContainer,
  CHeader,
  CHeaderNav,
  CNavItem,
  CNavLink,
} from '@coreui/react'
import React from 'react'

import {AppHeaderDropdown} from './Header/index'

const AppHeader = () => {

  return (
    <CHeader position="sticky" className="mb-2">
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
    </CHeader>
  )
}

export default AppHeader
