import {
  cilArrowLeft,
  cilEducation,
  cilInstitution,
  cilNotes,
  cilPeople,
  cilSettings,
  cilSpeedometer
} from '@coreui/icons'
import CIcon from '@coreui/icons-react'
import {
  CNavItem,
  CSidebar,
  CSidebarBrand,
  CSidebarHeader,
  CSidebarNav,
  CSidebarToggler
} from '@coreui/react'
import axios from 'axios'
import React, {
  memo,
  useState
} from 'react'
import SimpleBar from 'simplebar-react'
import './AppSidebar.scss'
import { AppSidebarNav } from './AppSidebarNav'
import * as LoadState from '../Helper/LoadState'
import { Link } from 'react-router-dom'
import { CssHelper } from '../Helper/CssHelper'

const AppSidebar = ({ isSidebarVisible, setIsSidebarVisible }) => {
  const [isSidebarUnfoldable, setIsSidebarUnfoldable] = useState(false)
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

  const navigation = []
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
      } else if (navItem.type === 'application') {
        navigation.push({
          component: CNavItem,
          name: navItem.title,
          to: navItem.to,
          icon: <CIcon icon={cilNotes} customClassName="nav-icon"/>
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
      } else if (navItem.type === 'settings') {
        navigation.push({
          component: CNavItem,
          name: navItem.title,
          to: navItem.to,
          icon: <CIcon icon={cilSettings} customClassName="nav-icon"/>
        })
      } else if (navItem.type === 'institution') {
        navigation.push({
          component: CNavItem,
          name: navItem.title,
          to: navItem.to,
          icon: <CIcon icon={cilInstitution} customClassName="nav-icon"/>
        })
      }
    })
  }
  const onToggleUnfoldable = () => {
    setIsSidebarUnfoldable(!isSidebarUnfoldable)
  }
  return (
    <CSidebar
      className={CssHelper.getCurrentSectionBgColor()}
      position="fixed"
      unfoldable={isSidebarUnfoldable}
      visible={isSidebarVisible}
      onVisibleChange={(visible) => {
        setIsSidebarVisible(visible)
      }}
    >
      <CSidebarHeader>
        <Link to={window.abeApp.urls.home} className="text-decoration-none">
          <CSidebarBrand className="d-md-flex">
            <CIcon className="sidebar-brand-full" icon={cilArrowLeft} height={25}/>
            <p className="mb-0 ms-2">Switch section</p>
          </CSidebarBrand>
        </Link>
      </CSidebarHeader>
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
