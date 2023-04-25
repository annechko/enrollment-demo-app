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
  CTableRow, CSpinner,
} from '@coreui/react'
import {Link} from "react-router-dom";
import PropTypes from "prop-types";
import React from "react";
import AppErrorMessage from "../../components/AppErrorMessage";

const CourseList = ({dataState}) => {
  const items = dataState.data
  let rows = []
  let key = 0
  if (dataState.error !== null) {
    return <AppErrorMessage error={dataState.error}/>
  }
  if (dataState.loaded === true) {
    items.forEach((item) => {
      rows.push((
        <CTableRow key={key++}>
          <CTableDataCell scope="row">{item.id.substring(0, 8) + '...'}</CTableDataCell>
          <CTableDataCell>{item.name}</CTableDataCell>
          <CTableDataCell>{item.description}</CTableDataCell>
          <CTableDataCell>
            <Link to={window.abeApp.urls.school_course_edit.replace(':id', item.id)}>
              <CButton color="primary" role="button" className="mb-3" size="sm" variant="outline">
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
        <CTableHeaderCell scope="row"><CSpinner color="primary"/></CTableHeaderCell>
        <CTableDataCell></CTableDataCell>
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
            <strong>School courses</strong>
          </CCardHeader>
          <CCardBody>
            <p>The course catalogue includes on-site and online courses.</p>
            <Link to={window.abeApp.urls.school_course_add}>
              <CButton color="primary" role="button" className="mb-3">
                New
              </CButton>
            </Link>
            <CTable hover bordered>
              <CTableHead>
                <CTableRow key={key++}>
                  <CTableHeaderCell scope="col">Id</CTableHeaderCell>
                  <CTableHeaderCell scope="col">Name</CTableHeaderCell>
                  <CTableHeaderCell scope="col">Description</CTableHeaderCell>
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
CourseList.propTypes = {
  dataState: PropTypes.shape({
      data: PropTypes.arrayOf(
        PropTypes.shape({
          id: PropTypes.string.isRequired,
          name: PropTypes.string.isRequired,
          description: PropTypes.string
        })
      ),
      loading: PropTypes.bool,
      loaded: PropTypes.bool,
      error: PropTypes.string,
    }
  ),
}
export default CourseList
