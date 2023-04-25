import React, {useState} from 'react'
import {
  CCard,
  CCardBody,
  CCardHeader,
  CCol,
  CRow,
  CTable,
  CTableBody,
  CButton,
  CTableDataCell,
  CTableHead,
  CTableHeaderCell,
  CTableRow,
} from '@coreui/react'
import axios from "axios";
import {Link} from "react-router-dom";

const CampusesTable = ({state}) => {
  let rows = []
  if (state.items !== null) {
    state.items.forEach((item) => {
      rows.push((
        <CTableRow>
          <CTableDataCell scope="row">{item.id.substr(0, 8) + '...'}</CTableDataCell>
          <CTableDataCell scope="row">{item.name}</CTableDataCell>
          <CTableDataCell>{item.campuses}</CTableDataCell>
          <CTableDataCell>{item.startDates}</CTableDataCell>
          <CTableDataCell>
            <Link to={window.abeApp.urls.COURSE_EDIT.replace(':id', item.id)}>
              <CButton color="primary" role="button" className="mb-3">
                Edit
              </CButton>
            </Link>
          </CTableDataCell>
        </CTableRow>
      ))
    })
  } else {
    rows.push((
      <CTableRow className="rows-loading">
        <CTableHeaderCell scope="row">Loading, please wait...</CTableHeaderCell>
        <CTableDataCell></CTableDataCell>
        <CTableDataCell></CTableDataCell>
        <CTableDataCell></CTableDataCell>
        <CTableDataCell></CTableDataCell>
      </CTableRow>
    ))
  }
  return (
    <CTable hover bordered>
      <CTableHead>
        <CTableRow>
          <CTableHeaderCell scope="col">Id</CTableHeaderCell>
          <CTableHeaderCell scope="col">Name</CTableHeaderCell>
          <CTableHeaderCell scope="col">Campuses</CTableHeaderCell>
          <CTableHeaderCell scope="col">Start dates</CTableHeaderCell>
          <CTableHeaderCell scope="col">Actions</CTableHeaderCell>
        </CTableRow>
      </CTableHead>
      <CTableBody>
        {rows}
      </CTableBody>
    </CTable>
  )
}
const CourseList = () => {

  const [itemsState, setItemsState] = useState({
    items: null,
    loading: false,
    loaded: false,
    error: null
  })
  const onLoad = (response) => {
    setItemsState({
      items: response.data,
      loading: false,
      loaded: true,
      error: null
    })
  }
  const onError = (error) => {
    setItemsState({
      items: null,
      loading: false,
      loaded: false,
      error: error.response?.data?.error || 'Something went wrong'
    })
  }
  const loadNavItems = () => {
    setItemsState({
      items: null,
      loading: true,
      loaded: false,
      error: null
    })
    const urls = window.abeApp.urls

    axios.get(urls.api.COURSES_GET)
      .then(onLoad)
      .catch(onError)
  }
  React.useEffect(() => {
    if (!itemsState.loaded && !itemsState.loading && itemsState.error === null) {
      loadNavItems()
    }
  }, [itemsState.loaded, itemsState.loading, itemsState.error])

  return (
    <CRow>
      <CCol xs={12}>
        <CCard className="mb-4">
          <CCardHeader>
            <strong>School courses</strong>
          </CCardHeader>
          <CCardBody>
            <p>sdsdsd</p>
            <Link to={window.abeApp.urls.COURSE_ADD}>
              <CButton color="primary" role="button" className="mb-3">
                New
              </CButton>
            </Link>
            <CampusesTable state={itemsState}/>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  )
}

export default CourseList
