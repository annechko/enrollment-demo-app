import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CCol,
  CRow,
  CSpinner,
  CTable,
  CTableBody,
  CTableDataCell,
  CTableHead,
  CTableHeaderCell,
  CTableRow,
} from '@coreui/react'
import PropTypes from "prop-types";
import React from "react";
import {Link} from "react-router-dom";
import AppErrorMessage from "../../../../App/Common/AppErrorMessage";
import CIcon from "@coreui/icons-react";
import {cilPencil} from "@coreui/icons";

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
          <CTableDataCell scope="row">{item.id.substring(32)}</CTableDataCell>
          <CTableDataCell>{item.name}</CTableDataCell>
          <CTableDataCell>{item.description}</CTableDataCell>
          <CTableDataCell>
            <Link to={window.abeApp.urls.school_course_edit.replace(':id', item.id)}>
              <CButton color="primary" role="button"
                className="pb-0 pt-0 pl-1 pr-1"
                size="sm" variant="outline">
                <CIcon icon={cilPencil}/>
              </CButton>
            </Link>
          </CTableDataCell>
        </CTableRow>
      ))
    })
  } else {
    rows.push((
      <CTableRow key={key++} className="app-loading">
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
          description: PropTypes.string,
        })
      ),
      loading: PropTypes.bool,
      loaded: PropTypes.bool,
      error: PropTypes.string,
    }
  ),
}
export default React.memo(CourseList)
