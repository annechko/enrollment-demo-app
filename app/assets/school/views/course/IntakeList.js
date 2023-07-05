import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CModal,
  CModalBody,
  CModalHeader,
  CModalTitle,
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
import IntakeForm from "./IntakeForm";

const IntakeAdd = ({dataState, courseId, reloadAfterAdd}) => {
  const [visible, setVisible] = React.useState(false)
  const [intakeAddState, setIntakeAddState] = React.useState({
    loading: false,
    error: null,
  })

  let campusOptions = [{
    value: '',
    label: dataState.loading ? 'Loading...' : '',
  }];

  if (dataState.error !== null) {
    return <AppErrorMessage error={dataState.error}/>
  }
  if (dataState.loaded === true) {
    dataState.data.forEach((item) => {
      campusOptions.push({
        value: item.id,
        label: item.name,
      })
    })
  }
  const formId = 'intake'
  const onAdded = () => {
    setVisible(false)
    reloadAfterAdd()
  }
  return <>
    <CButton color="primary" role="button" size="sm"
      onClick={() => setVisible(!visible)}>New</CButton>
    <CModal visible={visible} onClose={() => setVisible(false)}>
      <CModalHeader onClose={() => setVisible(false)}>
        <CModalTitle>Add new intake</CModalTitle>
      </CModalHeader>
      <CModalBody>
        <IntakeForm formId={formId}
          showSubmitBtn
          courseId={courseId}
          campusOptions={campusOptions}
          onSuccess={onAdded}
        />
      </CModalBody>

    </CModal>
  </>
}
const IntakeList = ({courseId}) => {
  return (
    <CCard className="mb-4">
      <CCardHeader>
        <strong>Intakes</strong>
      </CCardHeader>
      <CCardBody>
        <Loadable
          Component={IntakesRows}
          url={window.abeApp.urls.api_school_course_intake_list.replace(':id', courseId)}
          courseId={courseId}
        />
      </CCardBody>
    </CCard>
  )
}
const IntakesRows = ({dataState, reload, courseId}) => {
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
    <Loadable
      Component={IntakeAdd}
      url={window.abeApp.urls.api_school_campus_list}
      courseId={courseId}
      reloadAfterAdd={reload}
    />
  </>
}

IntakeList.propTypes = {
  courseId: PropTypes.string.isRequired,
}
IntakeAdd.propTypes = {
  courseId: PropTypes.string.isRequired,
  // reload: PropTypes.func.isRequired,
  dataState: PropTypes.shape({
    data: PropTypes.arrayOf(
      PropTypes.shape({
        id: PropTypes.string.isRequired,
        name: PropTypes.string.isRequired,
      })
    ),
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string,
  }),
}
IntakesRows.propTypes = {
  courseId: PropTypes.string.isRequired,
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
  }),
}
export default React.memo(IntakeList)
