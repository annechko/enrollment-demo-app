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
import React, {
  useEffect,
  useState
} from 'react'
import CIcon from "@coreui/icons-react";
import {cilPencil} from "@coreui/icons";
import Loadable from "../../pages/Loadable";
import AppErrorMessage from "../../components/AppErrorMessage";
import IntakeForm from "./IntakeForm";
import axios from "axios";
import PropTypes from "prop-types";

const IntakeList = ({courseId}) => {
  const [intakeModalState, setIntakeModalState] = React.useState({id: null, visible: false})
  // -------------
  const [campusListState, setCampusListState] = useState({
    data: null,
    loading: false,
    loaded: false,
    error: null
  })
  const loadCampusList = () => {
    setCampusListState({
      data: null,
      loading: true,
      loaded: false,
      error: null
    })
    axios.get(window.abeApp.urls.api_school_campus_list)
      .then((response) => {
        setCampusListState({
          data: response.data,
          loading: false,
          loaded: true,
          error: null
        })
      })
      .catch((error) => {
        setCampusListState({
          data: null,
          loading: false,
          loaded: false,
          error: error.response?.data?.error || 'Something went wrong'
        })
      })
  }
  useEffect(() => {
    if (!campusListState.loaded && !campusListState.loading && campusListState.error === null) {
      loadCampusList()
    }
  }, [campusListState])
  // -------------
  const [intakesState, setIntakesState] = useState({
    data: null,
    loading: false,
    loaded: false,
    error: null
  })
  const loadIntakes = () => {
    setIntakesState({
      data: null,
      loading: true,
      loaded: false,
      error: null
    })
    axios.get(window.abeApp.urls.api_school_course_intake_list.replace(':id', courseId))
      .then((response) => {
        setIntakesState({
          data: response.data,
          loading: false,
          loaded: true,
          error: null
        })
      })
      .catch((error) => {
        setIntakesState({
          data: null,
          loading: false,
          loaded: false,
          error: error.response?.data?.error || 'Something went wrong'
        })
      })
  }
  useEffect(() => {
    if (!intakesState.loaded && !intakesState.loading && intakesState.error === null) {
      loadIntakes()
    }
  }, [intakesState])
  // -------------

  let campusOptions = [{
    value: '',
    label: campusListState.loading ? 'Loading...' : '',
  }];

  if (campusListState.error !== null || intakesState.error !== null) {
    return <AppErrorMessage error={campusListState.error}/>
  }
  const formId = 'intake'
  if (campusListState.loaded === true) {
    campusListState.data.forEach((item) => {
      campusOptions.push({
        value: item.id,
        label: item.name,
      })
    })
  }
  const closeModal = () => setIntakeModalState({id: null, visible: false})
  const onModalExecuted = () => {
    closeModal();
    loadIntakes()
  }
  return (
    <CCard className="mb-4">
      <CCardHeader>
        <strong>Intakes</strong>
      </CCardHeader>
      <CCardBody>
        <IntakesRows
          intakesState={intakesState}
          setIntakeModalState={setIntakeModalState}
          intakeModalState={intakeModalState}
        />
        <CModal visible={intakeModalState.visible} onClose={closeModal}>
          <CModalHeader onClose={closeModal}>
            <CModalTitle>{intakeModalState.id === null ? 'Add new intake' : 'Edit intake'}</CModalTitle>
          </CModalHeader>
          <CModalBody>

            {intakeModalState.id === null
              ?
              <IntakeForm formId={formId}
                showSubmitBtn
                courseId={courseId}
                campusOptions={campusOptions}
                onSuccess={onModalExecuted}
              />
              :
              <Loadable component={IntakeForm}
                url={window.abeApp.urls.api_school_course_intake
                  .replace(':courseId', courseId).replace(':intakeId', intakeModalState.id)}
                isUpdate
                formId={formId}
                showSubmitBtn
                courseId={courseId}
                campusOptions={campusOptions}
                onSuccess={onModalExecuted}
              />
            }
          </CModalBody>
        </CModal>
      </CCardBody>
    </CCard>
  )
}
const IntakesRows = ({intakesState, setIntakeModalState, intakeModalState,}) => {
  const intakes = intakesState.data

  let key = 0
  if (intakesState.error !== null) {
    return <AppErrorMessage error={intakesState.error}/>
  }
  if (intakesState.loaded === false) {
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
        <CButton color="primary" role="button"
          className="pb-0 pt-0 pl-1 pr-1"
          onClick={() => {
            setIntakeModalState({id: item.id, visible: true});
          }}
          size="sm" variant="outline">
          <CIcon icon={cilPencil}/>
        </CButton>
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
    <CButton color="primary" role="button" size="sm"
      onClick={() => setIntakeModalState({id: null, visible: !intakeModalState.visible})}
    >New</CButton>
  </>
}

IntakeList.propTypes = {
  courseId: PropTypes.string.isRequired,
}
IntakesRows.propTypes = {
  setIntakeModalState: PropTypes.func.isRequired,
  intakeModalState: PropTypes.shape({
    id: PropTypes.string,
    visible: PropTypes.bool,
  }),
  intakesState: PropTypes.shape({
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
  }),
}
export default React.memo(IntakeList)
