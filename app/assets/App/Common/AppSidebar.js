import {
  cilArrowLeft,
  cilBaby,
  cilEducation,
  cilInstitution,
  cilPeople,
  cilSpeedometer
} from "@coreui/icons";
import CIcon from '@coreui/icons-react'
import {
  CNavItem,
  CSidebar,
  CSidebarBrand,
  CSidebarNav,
  CSidebarToggler
} from '@coreui/react'
import axios from "axios";
import React, {
  memo,
  useState
} from 'react'
import SimpleBar from 'simplebar-react'
import './AppSidebar.scss'
import {AppSidebarNav} from './AppSidebarNav'
import * as LoadState from "../Helper/LoadState";
import {Link} from "react-router-dom";

const AppSidebar = () => {
  const [unfoldable, toogleUnfoldable] = useState(false)
  const [navItemsState, setNavItemsState] = useState(LoadState.initialize())
  React.useEffect(() => {
    if (LoadState.needLoading(navItemsState)) {
      loadNavItems()
    }
  }, [navItemsState])

  const onSuccess = (response) => {
    setNavItemsState(LoadState.finishLoading(response.data.navItems))
  }
  const onError = (error) => {
    setNavItemsState(LoadState.error(error.response?.data?.error))
  }
  const loadNavItems = () => {
    setNavItemsState(LoadState.startLoading())

    axios.get(window.abeApp.urls.api_sidebar)
      .then(onSuccess)
      .catch(onError)
  }

  let navigation = []
  if (navItemsState.data === null) {
    navigation.push({
      component: CNavItem,
      name: 'Loading...',
      to: '/',
      className: 'disabled'
    })
  } else {
    navItemsState.data.forEach((navItem) => {
      // todo add item types and map them to icons
      if (navItem.type === 'home') {
        navigation.push({
          component: CNavItem,
          name: navItem.title,
          to: navItem.to,
          icon: <CIcon icon={cilSpeedometer} customClassName="nav-icon"/>
        })
      } else if (navItem.type === 'campuses') {
        navigation.push({
          component: CNavItem,
          name: navItem.title,
          to: navItem.to,
          icon: <CIcon icon={cilInstitution} customClassName="nav-icon"/>
        })
      } else if (navItem.type === 'courses') {
        navigation.push({
          component: CNavItem,
          name: navItem.title,
          to: navItem.to,
          icon: <CIcon icon={cilEducation} customClassName="nav-icon"/>
        })
      } else if (navItem.type === 'students') {
        navigation.push({
          component: CNavItem,
          name: navItem.title,
          to: navItem.to,
          icon: <CIcon icon={cilPeople} customClassName="nav-icon"/>
        })
      }
    })

  }
  const onToggleUnfoldable = () => {
    toogleUnfoldable(!unfoldable)
  }
  return (
    <CSidebar
      position="fixed"
      unfoldable={unfoldable}
      visible={true}
    >
      <Link to={window.abeApp.urls.home} className="text-decoration-none">
        <CSidebarBrand className="d-md-flex" style={{minHeight: '61px'}}>
          <CIcon className="sidebar-brand-full" icon={cilArrowLeft} height={25} />
          <p className="mb-0 ms-2">Switch section</p>
        </CSidebarBrand>
      </Link>
      <CSidebarNav>
        <SimpleBar>
          <AppSidebarNav items={navigation}/>
        </SimpleBar>
      </CSidebarNav>
      <CSidebarToggler
        className="d-none d-lg-flex"
        onClick={onToggleUnfoldable}
      />
    </CSidebar>
  )
}

export default memo(AppSidebar)
