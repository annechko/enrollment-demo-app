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
import {Link} from "react-router-dom";
import PropTypes from "prop-types";
import React from "react";

const CampusList = ({items}) => {
  let rows = []
  let key = 0
  if (items !== null) {
    items.forEach((item) => {
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
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  )
}
CampusList.propTypes = {
  items: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.string.isRequired,
      name: PropTypes.string.isRequired,
      address: PropTypes.string
    })
  ),
}
export default CampusList
