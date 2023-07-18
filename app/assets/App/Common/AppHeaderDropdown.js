import {
  cilAccountLogout,
  cilBell,
  cilCommentSquare,
  cilEnvelopeOpen,
  cilSettings,
  cilTask,
  cilUser,
} from '@coreui/icons'
import CIcon from '@coreui/icons-react'
import {
  CBadge,
  CDropdown,
  CDropdownDivider,
  CDropdownHeader,
  CDropdownItem,
  CDropdownMenu,
  CDropdownToggle,
} from '@coreui/react'
import React, {
  memo,
  useContext
} from 'react'
import {UserContext} from "../Helper/Context/UserContext";
import {OtherAccounts} from "../Helper/Context/OtherAccountsContext";
import {CurrentSectionContext} from "../Helper/Context/CurrentSectionContext";

const OtherProfiles = () => {
  const otherAccounts = useContext(OtherAccounts)
  if (otherAccounts.length === 0) {
    return <></>
  }
  return <>
    <CDropdownHeader className="bg-light fw-semibold py-2">Other Accounts</CDropdownHeader>
    {
      otherAccounts.map((account, index) =>
        <CDropdownItem href={account.home} key={index}>
          <CIcon icon={cilUser} className="me-2"/>
          {account.email}
        </CDropdownItem>
      )
    }
  </>
}
const AppHeaderDropdown = () => {
  const user = useContext(UserContext)
  const currentSection = useContext(CurrentSectionContext)
  let avatarColor = ''
  if (currentSection === 'admin') {
    avatarColor = 'bg-danger'
  } else if (currentSection === 'school') {
    avatarColor = 'bg-info'
  }
  return (
    <CDropdown variant="nav-item">
      <CDropdownToggle placement="bottom-end" className="py-0" caret={false}>
        <div className={'fake-avatar avatar-img ' + avatarColor}></div>
      </CDropdownToggle>
      <CDropdownMenu className="pt-0" placement="bottom-end">
        <CDropdownHeader className="bg-light fw-semibold py-2">Logged in as</CDropdownHeader>
        <CDropdownItem href="#">
          <CIcon icon={cilUser} className="me-2"/>
          {user.email}
        </CDropdownItem>

        <OtherProfiles></OtherProfiles>

        <CDropdownHeader className="bg-light fw-semibold py-2">Activity</CDropdownHeader>
        <CDropdownItem href="#">
          <CIcon icon={cilBell} className="me-2"/>
          Updates
          <CBadge color="info" className="ms-2">
            42
          </CBadge>
        </CDropdownItem>
        <CDropdownItem href="#">
          <CIcon icon={cilEnvelopeOpen} className="me-2"/>
          Messages
          <CBadge color="success" className="ms-2">
            42
          </CBadge>
        </CDropdownItem>
        <CDropdownItem href="#">
          <CIcon icon={cilTask} className="me-2"/>
          Tasks
          <CBadge color="danger" className="ms-2">
            42
          </CBadge>
        </CDropdownItem>
        <CDropdownItem href="#">
          <CIcon icon={cilCommentSquare} className="me-2"/>
          Comments
          <CBadge color="warning" className="ms-2">
            42
          </CBadge>
        </CDropdownItem>
        <CDropdownHeader className="bg-light fw-semibold py-2">Settings</CDropdownHeader>
        <CDropdownItem href="#">
          <CIcon icon={cilUser} className="me-2"/>
          Profile
        </CDropdownItem>
        <CDropdownItem href="#">
          <CIcon icon={cilSettings} className="me-2"/>
          Settings
        </CDropdownItem>
        <CDropdownDivider/>
        <CDropdownItem href={window.abeApp.urls.logout}>
          <CIcon icon={cilAccountLogout} className="me-2"/>
          Logout
        </CDropdownItem>
      </CDropdownMenu>
    </CDropdown>
  )
}

export default memo(AppHeaderDropdown)
