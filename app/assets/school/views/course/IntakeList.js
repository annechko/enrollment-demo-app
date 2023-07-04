import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CTable,
  CTableBody,
  CTableDataCell,
  CTableHead,
  CTableHeaderCell,
  CTableRow,
} from '@coreui/react'
import PropTypes from "prop-types";
import React, {useState} from 'react'
import {Link} from "react-router-dom";
import CIcon from "@coreui/icons-react";
import {cilPencil} from "@coreui/icons";

const IntakeList = ({
                   intakes,
                   campuses,
                 }) => {
  let campusOptions = [{
    value: '',
    label: 'Select a campus',
  }];

  campuses.forEach((item) => {
    campusOptions.push({
      value: item.id,
      label: item.name,
    })
  })

  const [intakesState, setIntakesState] = useState(intakes)

  const addIntake = () => {
    let updated = Array.from(intakesState)
    updated.push({
      id: null,
      name: null,
      campus: null,
      startDate: null,
      endDate: null,
      classSize: null,
    })
    setIntakesState(updated)
  }
  const deleteIntake = (index) => {
    let updated = Array.from(intakesState)
    updated.splice(index, 1)
    setIntakesState(updated)
  }

  let key = 0
  const intakesRows = intakes.map((item) =>
    <CTableRow key={key++}>
      <CTableDataCell scope="row">{item.id.substring(32)}</CTableDataCell>
      <CTableDataCell>{item.name}</CTableDataCell>
      <CTableDataCell className="text-nowrap">{item.startDate}</CTableDataCell>
      <CTableDataCell className="text-nowrap">{item.endDate}</CTableDataCell>
      <CTableDataCell>{item.classSize}</CTableDataCell>
      <CTableDataCell>{item.campus}</CTableDataCell>
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
  )

  return (
    <CCard className="mb-4">
      <CCardHeader>
        <strong>Intakes</strong>
      </CCardHeader>
      <CCardBody>
        {intakesRows.length > 0 && <CTable hover bordered>
          <CTableHead>
            <CTableRow key={key++}>
              <CTableHeaderCell scope="col">Id</CTableHeaderCell>
              <CTableHeaderCell scope="col">Name</CTableHeaderCell>
              <CTableHeaderCell scope="col">startDate</CTableHeaderCell>
              <CTableHeaderCell scope="col">endDate</CTableHeaderCell>
              <CTableHeaderCell scope="col">classSize</CTableHeaderCell>
              <CTableHeaderCell scope="col">campus</CTableHeaderCell>
              <CTableHeaderCell scope="col">Actions</CTableHeaderCell>
            </CTableRow>
          </CTableHead>
          <CTableBody>
            {intakesRows}
          </CTableBody>
        </CTable>}
        <Link to={window.abeApp.urls.school_course_add}>
          <CButton color="primary" role="button" size="sm" >
            New
          </CButton>
        </Link>

      </CCardBody>
    </CCard>
  )
}
IntakeList.propTypes = {
  intakes: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.string,
      name: PropTypes.string,
      campus: PropTypes.string,
      startDate: PropTypes.string,
      endDate: PropTypes.string,
      classSize: PropTypes.number,
    })
  ),
  campuses: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.string.isRequired,
      name: PropTypes.string.isRequired,
    })
  ),
}
export default React.memo(IntakeList)
