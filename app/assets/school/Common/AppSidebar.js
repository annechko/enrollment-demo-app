import {
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

const AppSidebar = () => {
  const [unfoldable, toogleUnfoldable] = useState(false)
  const [navItemsState, setNavItemsState] = useState({
    navItems: null,
    loading: false,
    loaded: false,
    error: null
  })
  const onLoad = (response) => {
    setNavItemsState({
      navItems: response.data.navItems,
      loading: false,
      loaded: true,
      error: null
    })
  }
  const onError = (error) => {
    setNavItemsState({
      navItems: null,
      loading: false,
      loaded: false,
      error: error.response?.data?.error || 'Something went wrong'
    })
  }
  const loadNavItems = () => {
    setNavItemsState({
      navItems: null,
      loading: true,
      loaded: false,
      error: null
    })
    const urls = window.abeApp.urls

    axios.get(urls.api_school_sidebar)
      .then(onLoad)
      .catch(onError)
  }
  React.useEffect(() => {
    if (!navItemsState.loaded && !navItemsState.loading && navItemsState.error === null) {
      loadNavItems()
    }
  }, [navItemsState.loaded, navItemsState.loading, navItemsState.error])


  let navigation = []
  if (navItemsState.navItems === null) {
    navigation.push({
      component: CNavItem,
      name: 'Loading...',
      to: '/',
      className: 'disabled'
    })
  } else {
    navItemsState.navItems.forEach((navItem) => {
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
      <CSidebarBrand className="d-md-flex" to="/">
        <CIcon className="sidebar-brand-full" icon={cilBaby} height={35}/>
      </CSidebarBrand>
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
