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
  let key = 0
  if (state.items !== null) {
    state.items.forEach((item) => {
      rows.push((
        <CTableRow key={key++}>
          <CTableDataCell scope="row">{item.name}</CTableDataCell>
          <CTableDataCell>{item.address}</CTableDataCell>
          <CTableDataCell>
            <Link to={window.abeApp.urls.school_campus_edit.replace(':id', item.id)}>
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
      <CTableRow key={key++} className="rows-loading">
        <CTableHeaderCell scope="row">Loading, please wait...</CTableHeaderCell>
        <CTableDataCell></CTableDataCell>
        <CTableDataCell></CTableDataCell>
      </CTableRow>
    ))
  }
  return (
    <CTable hover bordered>
      <CTableHead>
        <CTableRow key={key++}>
          <CTableHeaderCell scope="col">Name</CTableHeaderCell>
          <CTableHeaderCell scope="col">Address</CTableHeaderCell>
          <CTableHeaderCell scope="col">Actions</CTableHeaderCell>
        </CTableRow>
      </CTableHead>
      <CTableBody>
        {rows}
      </CTableBody>
    </CTable>
  )
}
const Campuses = () => {

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

    axios.get(urls.api_school_campus_list)
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
            <strong>School campuses</strong>
          </CCardHeader>
          <CCardBody>
            <p>The buildings, structures, and outdoor areas available for use by children attending the school.</p>
            <Link to={window.abeApp.urls.school_campus_add}>
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

export default Campuses
