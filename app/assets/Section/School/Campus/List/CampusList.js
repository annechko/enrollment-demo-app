import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CCol,
  CRow,
  CTable,
  CTableBody,
  CTableDataCell,
  CTableHead,
  CTableHeaderCell,
  CTableRow
} from '@coreui/react'
import PropTypes from 'prop-types'
import React from 'react'
import { Link } from 'react-router-dom'
import CIcon from '@coreui/icons-react'
import { cilPencil } from '@coreui/icons'
import AppDataLoader from '../../../../App/Common/AppDataLoader'

const CampusList = ({ dataState }) => {
  const items = dataState.data
  const rows = []
  let key = 0
  if (dataState.error !== null) {
    return (<div>
      Error: {dataState.error}
    </div>)
  }
  if (dataState.loaded === true) {
    items.forEach((item) => {
      rows.push((
        <CTableRow key={key++}>
          <CTableDataCell scope="row">{item.name}</CTableDataCell>
          <CTableDataCell>{item.address}</CTableDataCell>
          <CTableDataCell>
            <Link to={window.abeApp.urls.school_campus_edit.replace(':id', item.id)}>
              <CButton color="primary" role="button"
                data-testid="btn-edit-campus"
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
        <CTableHeaderCell scope="row"><AppDataLoader/></CTableHeaderCell>
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
          <CCardBody className="overflow-y-scroll">
            <p>The buildings, structures, and outdoor areas available for use by children attending the school.</p>
            <Link to={window.abeApp.urls.school_campus_add}>
              <CButton color="primary" role="button" className="mb-3" size="sm" data-testid="btn-add-campus">
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
  dataState: PropTypes.shape({
      data: PropTypes.arrayOf(
        PropTypes.shape({
          id: PropTypes.string.isRequired,
          name: PropTypes.string.isRequired,
          address: PropTypes.string
        })
      ),
      loading: PropTypes.bool,
      loaded: PropTypes.bool,
      error: PropTypes.string
    }
  )
}
export default CampusList
