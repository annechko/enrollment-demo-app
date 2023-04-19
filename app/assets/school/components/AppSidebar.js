import React, {useEffect, useState, memo} from 'react'
import {CNavItem, CSidebar, CSidebarBrand, CSidebarNav, CSidebarToggler} from '@coreui/react'
import CIcon from '@coreui/icons-react'
import {AppSidebarNav} from './AppSidebarNav'
import SimpleBar from 'simplebar-react'
import './AppSidebar.scss'

// import navigation from './_nav'
import {cilBaby, cilSpeedometer, cilAddressBook} from "@coreui/icons";
import axios from "axios";

const AppSidebar = () =>
{
	const [unfoldable, toogleUnfoldable] = useState(false)
	const [navItemsState, setNavItemsState] = useState({
		navItems: null,
		loading: false,
		loaded: false,
		error: null
	})
	const onLoad = (response) =>
	{
		setNavItemsState({
			navItems: response.data.navItems,
			loading: false,
			loaded: true,
			error: null
		})
	}
	const onError = (error) =>
	{
		setNavItemsState({
			navItems: null,
			loading: false,
			loaded: false,
			error: error.response?.data?.error || 'Something went wrong'
		})
	}
	const loadNavItems = () =>
	{
		setNavItemsState({
			navItems: null,
			loading: true,
			loaded: false,
			error: null
		})
		const urls = window.abeApp.urls

		axios.get(urls.api.GET_SIDEBAR)
			.then(onLoad)
			.catch(onError)
	}
	React.useEffect(() =>
	{
		if (!navItemsState.loaded && !navItemsState.loading && navItemsState.error === null)
		{
			loadNavItems()
		}
	}, [navItemsState.loaded, navItemsState.loading, navItemsState.error])


	let navigation = []
	if (navItemsState.navItems === null)
	{
		navigation.push({
			component: CNavItem,
			name: 'Loading...',
			to: '/',
			className: 'disabled'
		})
	}
	else
	{
		navItemsState.navItems.forEach((navItem) =>
		{
			if (navItem.type === 'home')
			{
				navigation.push({
					component: CNavItem,
					name: navItem.title,
					to: navItem.to,
					icon: <CIcon icon={cilSpeedometer} customClassName="nav-icon"/>
				})
			}
			else if (navItem.type === 'campuses')
			{
				navigation.push({
					component: CNavItem,
					name: navItem.title,
					to: navItem.to,
					icon: <CIcon icon={cilAddressBook} customClassName="nav-icon"/>
				})
			}
		})

	}
	const onToggleUnfoldable = () =>
	{
		toogleUnfoldable(!unfoldable)
	}
	return (
		<CSidebar
			position="fixed"
			unfoldable={unfoldable}
			visible={true}
		>
			<CSidebarBrand className="d-none d-md-flex" to="/">
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
