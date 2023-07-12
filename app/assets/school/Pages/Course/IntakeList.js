import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CModal,
  CModalBody,
  CModalFooter,
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
import {
  cilPencil,
  cilX
} from "@coreui/icons";
import Loadable from "../Loadable";
import AppErrorMessage from "../../Common/AppErrorMessage";
import IntakeForm from "./IntakeForm";
import axios from "axios";
import PropTypes from "prop-types";

const IntakeList = ({courseId}) => {
  const [newIntakeModalState, setNewIntakeModalState] = React.useState({id: null, visible: false})
  const [removeIntakeState, setRemoveIntakeState] = React.useState({modalVisible: false, intake: {}})
  const [removeIntakeRequestState, setRemoveIntakeRequestState] = useState({
    loading: false,
    loaded: false,
    error: null
  })
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
  const closeNewIntakeModal = () => setNewIntakeModalState({id: null, visible: false})
  const onNewIntakeModalExecuted = () => {
    closeNewIntakeModal();
    loadIntakes()
  }
  const intakeToRemove = removeIntakeState.intake
  const removeIntake = (intakeId) => {
    return () => {
      const url = window.abeApp.urls.api_school_course_intake_remove.replace(':courseId', courseId)
        .replace(':intakeId', intakeId)
      setRemoveIntakeRequestState({
        loading: true,
        loaded: false,
        error: null
      })
      axios.delete(url, {headers: {'Content-Type': 'multipart/form-data'}})
        .then(response => {
          setRemoveIntakeRequestState({
            loading: false,
            loaded: true,
            error: null
          })
          loadIntakes()
          // todo add success alert
          setRemoveIntakeState({modalVisible: false, intake: {}})
        })
        .catch((error) => {
          setRemoveIntakeRequestState({
            loading: false,
            loaded: false,
            error: error.response?.data?.error || 'Something went wrong'
          })
        });
    }
  }
  return (
    <CCard className="mb-4">
      <CCardHeader>
        <strong>Intakes</strong>
      </CCardHeader>
      <CCardBody>
        <IntakesRows
          intakesState={intakesState}
          setNewIntakeModalState={setNewIntakeModalState}
          newIntakeModalState={newIntakeModalState}
          setRemoveIntakeState={setRemoveIntakeState}
        />
        <CModal visible={newIntakeModalState.visible} onClose={closeNewIntakeModal}>
          <CModalHeader onClose={closeNewIntakeModal}>
            <CModalTitle>{newIntakeModalState.id === null ? 'Add new intake' : 'Edit intake'}</CModalTitle>
          </CModalHeader>
          <CModalBody>
            {newIntakeModalState.id === null
              ?
              <IntakeForm formId={formId}
                showSubmitBtn
                courseId={courseId}
                campusOptions={campusOptions}
                onSuccess={onNewIntakeModalExecuted}
              />
              :
              <Loadable component={IntakeForm}
                url={window.abeApp.urls.api_school_course_intake
                  .replace(':courseId', courseId).replace(':intakeId', newIntakeModalState.id)}
                isUpdate
                formId={formId}
                showSubmitBtn
                courseId={courseId}
                campusOptions={campusOptions}
                onSuccess={onNewIntakeModalExecuted}
              />
            }
          </CModalBody>
        </CModal>

        <CModal visible={removeIntakeState.modalVisible}
          onClose={() => {
            setRemoveIntakeState({modalVisible: false, intake: {}})
          }}>
          <CModalHeader onClose={() => {
            setRemoveIntakeState({modalVisible: false, intake: {}})
          }}>
            <CModalTitle>Remove intake</CModalTitle>
          </CModalHeader>
          <CModalBody>
            {removeIntakeRequestState.error !== null && <AppErrorMessage error={removeIntakeRequestState.error}/>}
            Are you sure you want to remove intake "{intakeToRemove.name}"?
            Dates: {intakeToRemove.start} - {intakeToRemove.end}
          </CModalBody>
          <CModalFooter>
            <CButton color="secondary" size="sm" onClick={() => setRemoveIntakeState({
              modalVisible: false,
              intake: {}
            })}>
              Close
            </CButton>
            <CButton color="danger" size="sm"
              disabled={removeIntakeRequestState.loading === true}
              onClick={removeIntake(intakeToRemove.id)}>
              {removeIntakeRequestState.loading === true
                && <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}

              Remove</CButton>
          </CModalFooter>
        </CModal>

      </CCardBody>
    </CCard>
  )
}
const IntakesRows = ({
                       intakesState,
                       setNewIntakeModalState,
                       setRemoveIntakeState,
                       newIntakeModalState,
                     }) => {
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
        <div className="d-flex">
          <CButton color="primary" role="button"
            className="py-0 me-1"
            onClick={() => {
              setNewIntakeModalState({id: item.id, visible: true});
            }}
            size="sm" variant="outline">
            <CIcon icon={cilPencil}/>
          </CButton>
          <CButton color="danger" role="button"
            className="py-0"
            onClick={() => {
              setRemoveIntakeState({
                modalVisible: true,
                intake: {name: item.name, id: item.id, start: item.startDate, end: item.endDate}
              });
            }}
            size="sm" variant="outline">
            <CIcon icon={cilX}/>
          </CButton>
        </div>
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
      onClick={() => setNewIntakeModalState({id: null, visible: !newIntakeModalState.visible})}
    >New</CButton>
  </>
}

IntakeList.propTypes = {
  courseId: PropTypes.string.isRequired,
}
IntakesRows.propTypes = {
  setNewIntakeModalState: PropTypes.func.isRequired,
  setRemoveIntakeState: PropTypes.func.isRequired,
  newIntakeModalState: PropTypes.shape({
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
