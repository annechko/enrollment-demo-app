import {
  cilAccountLogout,
  cilUser,
} from '@coreui/icons'
import CIcon from '@coreui/icons-react'
import {CssHelper} from "../Helper/CssHelper";
import {
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
  return (
    <CDropdown variant="nav-item">
      <CDropdownToggle placement="bottom-end" className="py-0" caret={false}>
        <div className={'fake-avatar avatar-img ' + CssHelper.getCurrentSectionBgColor()}></div>
      </CDropdownToggle>
      <CDropdownMenu className="pt-0" placement="bottom-end">
        <CDropdownHeader className="bg-light fw-semibold py-2">Logged in as</CDropdownHeader>
        <CDropdownItem href="#">
          <CIcon icon={cilUser} className="me-2"/>
          {user.email}
        </CDropdownItem>

        <OtherProfiles></OtherProfiles>

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
