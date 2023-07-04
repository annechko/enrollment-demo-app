import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CSpinner,
  CTable,
  CTableBody,
  CTableDataCell,
  CTableHead,
  CTableHeaderCell,
  CTableRow,
} from '@coreui/react'
import PropTypes from "prop-types";
import React from 'react'
import {Link} from "react-router-dom";
import CIcon from "@coreui/icons-react";
import {cilPencil} from "@coreui/icons";
import Loadable from "../../pages/Loadable";
import AppErrorMessage from "../../components/AppErrorMessage";

const IntakeList = ({courseId = ''}) => {
  return (
    <CCard className="mb-4">
      <CCardHeader>
        <strong>Intakes</strong>
      </CCardHeader>
      <CCardBody>
        <Loadable
          Component={IntakesRows}
          url={window.abeApp.urls.api_school_course_intake_list.replace(':id', courseId)}/>
      </CCardBody>
    </CCard>
  )
}
const IntakesRows = ({dataState, reload}) => {
  const intakes = dataState.data
  let key = 0
  if (dataState.error !== null) {
    return <AppErrorMessage error={dataState.error}/>
  }
  if (dataState.loaded === false) {
    return <CSpinner color="primary"/>
  }
  let intakesRows = []

  intakesRows = intakes.map((item) =>
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
  const onAddClick = () => {
  }
  return <>
    {intakesRows.length > 0
      &&
      <CTable hover bordered>
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
      <CButton color="primary" role="button" size="sm"
        onClick={onAddClick}
      >
        New
      </CButton>
    </Link>
  </>
}

IntakeList.propTypes = {
  courseId: PropTypes.string,
}
IntakesRows.propTypes = {
  dataState: PropTypes.shape({
      data: PropTypes.arrayOf(
        PropTypes.shape({
          id: PropTypes.string,
          name: PropTypes.string,
          campus: PropTypes.string,
          startDate: PropTypes.string,
          endDate: PropTypes.string,
          classSize: PropTypes.number,
        })
      ),
      loading: PropTypes.bool,
      loaded: PropTypes.bool,
      error: PropTypes.string,
    }
  ),
}
export default React.memo(IntakeList)
