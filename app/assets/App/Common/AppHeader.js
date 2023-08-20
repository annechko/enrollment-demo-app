import {
  CContainer,
  CHeader,
  CHeaderNav,
} from '@coreui/react'
import React, {useContext} from 'react'
import {AppHeaderDropdown} from "./index";
import {CurrentSectionContext} from "../Helper/Context/CurrentSectionContext";

const AppHeader = () => {
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
      <CContainer fluid className="header-items-right">
        {sectionName}
        <CHeaderNav className="ms-3">
          <AppHeaderDropdown/>
        </CHeaderNav>
      </CContainer>
    </CHeader>
  )
}

export default AppHeader
