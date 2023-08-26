import {
  CContainer,
  CHeader,
  CHeaderNav,
  CHeaderToggler,
} from '@coreui/react'
import React, {useContext} from 'react'
import {AppHeaderDropdown} from "./index";
import {CurrentSectionContext} from "../Helper/Context/CurrentSectionContext";
import CIcon from "@coreui/icons-react";
import {cilMenu} from "@coreui/icons";

const AppHeader = ({toggleSidebarVisible}) => {
  const currentSection = useContext(CurrentSectionContext)
  let sectionName = ''
  if (currentSection === 'admin') {
    sectionName = 'Admin section'
  } else if (currentSection === 'school') {
    sectionName = 'School section'
  } else if (currentSection === 'student') {
    sectionName = 'Student section'
  }
  return (
    <CHeader position="sticky" className="mb-2">
      <CContainer fluid>
        <CHeaderToggler
          className="ps-1"
          onClick={toggleSidebarVisible}
        >
          <CIcon icon={cilMenu} size="lg" width={30} height={30} className="app-menu-icon"/>
        </CHeaderToggler>
        {sectionName}
        <CHeaderNav className="ms-3">
          <AppHeaderDropdown/>
        </CHeaderNav>
      </CContainer>
    </CHeader>
  )
}

export default AppHeader
