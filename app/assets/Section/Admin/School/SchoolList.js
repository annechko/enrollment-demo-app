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
  cilCheck,
  cilX
} from "@coreui/icons";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";
import PropTypes from "prop-types";
import * as LoadState from "../../../App/Helper/LoadState";
import * as Api from "../../../App/Helper/Api";
import {submitForm} from "../../../App/Helper/SubmitForm";

const SchoolList = () => {
  const [confirmState, setConfirmState] = React.useState({modalVisible: false, school: {}})
  const [deleteState, setDeleteState] = React.useState({modalVisible: false, school: {}})

  const [schoolsState, setSchoolsState] = useState(LoadState.initialize())
  const schoolsUrl = window.abeApp.urls.api_admin_school_list
  const loadSchools = Api.loadData(schoolsUrl, setSchoolsState)
  useEffect(() => {
    if (LoadState.needLoading(schoolsState)) {
      loadSchools()
    }
  }, [schoolsState])

  const formId = 'confirm'
  const onConfirmSuccess = () => {
    setConfirmState({modalVisible: false, school: {}})
    loadSchools()
  }
  const confirm = (schoolId) => {
    return (event) => {
      const url = window.abeApp.urls.api_admin_school_confirm.replace(':schoolId', schoolId)
      submitForm({
        event,
        state: confirmState,
        setState: setConfirmState,
        formId,
        url,
        onSuccess: onConfirmSuccess
      })
    }
  }
  const onDeleteSuccess = () => {
    setDeleteState({modalVisible: false, school: {}})
    loadSchools()
  }
  const deleteSchool = (schoolId) => {
    return () => {
      const url = window.abeApp.urls.api_admin_school_delete.replace(':schoolId', schoolId)
      Api.submitData({
        state: deleteState,
        setState: setDeleteState,
        url,
        data: {schoolId: schoolId},
        onSuccess: onDeleteSuccess
      })
    }
  }
  return (
    <CCard className="mb-4">
      <CCardHeader>
        <strong>Schools</strong>
      </CCardHeader>
      <CCardBody className="overflow-y-scroll">
        <SchoolsRows
          schoolsState={schoolsState}
          setConfirmState={setConfirmState}
          setDeleteState={setDeleteState}
        />

        <ConfirmModal confirmState={confirmState} setConfirmState={setConfirmState} confirm={confirm}/>
        <DeleteModal state={deleteState} setState={setDeleteState} callback={deleteSchool}/>
      </CCardBody>
    </CCard>
  )
}
const DeleteModal = ({state, setState, callback}) => {
  return <CModal visible={state.modalVisible}
    onClose={() => {
      setState({modalVisible: false, school: {}})
    }}>
    <CModalHeader onClose={() => {
      setState({modalVisible: false, school: {}})
    }}>
      <CModalTitle>Confirm school's delete</CModalTitle>
    </CModalHeader>
    <CModalBody>
      {state.error !== null && <AppErrorMessage error={state.error}/>}
      Are you sure you want to delete school "{state.school.name}"?
      All its staff members, courses, intakes, student applications will be removed too.
    </CModalBody>
    <CModalFooter>
      <CButton color="secondary" size="sm" onClick={() => setState({
        modalVisible: false,
        school: {}
      })}>
        Close
      </CButton>
      <CButton color="danger" size="sm"
        disabled={state.loading === true}
        onClick={callback(state.school.id)}>

        {state.loading === true
          && <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}
        Confirm
      </CButton>
    </CModalFooter>
  </CModal>

}
const ConfirmModal = ({confirmState, setConfirmState, confirm}) => {
  return <CModal visible={confirmState.modalVisible}
    onClose={() => {
      setConfirmState({modalVisible: false, school: {}})
    }}>
    <CModalHeader onClose={() => {
      setConfirmState({modalVisible: false, school: {}})
    }}>
      <CModalTitle>Confirm school's registration</CModalTitle>
    </CModalHeader>
    <CModalBody>
      {confirmState.error !== null && <AppErrorMessage error={confirmState.error}/>}
      Are you sure you want to confirm school "{confirmState.school.name}"?
    </CModalBody>
    <CModalFooter>
      <CButton color="secondary" size="sm" onClick={() => setConfirmState({
        modalVisible: false,
        school: {}
      })}>
        Close
      </CButton>
      <CButton color="primary" size="sm"
        disabled={confirmState.loading === true}
        onClick={confirm(confirmState.school.id)}>

        {confirmState.loading === true
          && <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}
        Confirm
      </CButton>
    </CModalFooter>
  </CModal>
}
const SchoolsRows = ({
                       schoolsState,
                       setConfirmState,
                       setDeleteState,
                     }) => {
  const schools = schoolsState.data

  let key = 0
  if (schoolsState.error !== null) {
    return <AppErrorMessage error={schoolsState.error}/>
  }
  if (schoolsState.loaded === false) {
    return <CSpinner color="primary"/>
  }
  let schoolsRows = []

  schoolsRows = schools.map((item) =>
    <CTableRow key={key++}>
      <CTableDataCell scope="row">{item.id.substring(32)}</CTableDataCell>
      <CTableDataCell>{item.name}</CTableDataCell>
      <CTableDataCell>{item.email}</CTableDataCell>
      <CTableDataCell>{item.createdAt}</CTableDataCell>
      <CTableDataCell>{item.invitationDate}</CTableDataCell>
      <CTableDataCell>
        <div className="d-flex">
          {item.canBeConfirmed &&
            <CButton color="primary" role="button"
              className="py-0 me-1"
              onClick={() => {
                setConfirmState({
                  modalVisible: true,
                  school: {name: item.name, id: item.id}
                });
              }}
              size="sm" variant="outline">
              <CIcon icon={cilCheck}/>
            </CButton>}
          {item.canBeDeleted &&
            <CButton color="danger" role="button"
              className="py-0"
              onClick={() => {
                setDeleteState({
                  modalVisible: true,
                  school: {name: item.name, id: item.id}
                });
              }}
              size="sm" variant="outline">
              <CIcon icon={cilX}/>
            </CButton>}
        </div>
      </CTableDataCell>
    </CTableRow>
  )

  return <>
    {schoolsRows.length > 0
      &&
      <CTable hover bordered>
        <CTableHead>
          <CTableRow key={key++}>
            <CTableHeaderCell scope="col">Id</CTableHeaderCell>
            <CTableHeaderCell scope="col">Name</CTableHeaderCell>
            <CTableHeaderCell scope="col">Email</CTableHeaderCell>
            <CTableHeaderCell scope="col">Registration Date</CTableHeaderCell>
            <CTableHeaderCell scope="col">Invitation Date</CTableHeaderCell>
            <CTableHeaderCell scope="col">Actions</CTableHeaderCell>
          </CTableRow>
        </CTableHead>
        <CTableBody>
          {schoolsRows}
        </CTableBody>
      </CTable>}

  </>
}

SchoolsRows.propTypes = {
  setConfirmState: PropTypes.func.isRequired,
  setDeleteState: PropTypes.func.isRequired,
  schoolsState: PropTypes.shape({
    data: PropTypes.arrayOf(
      PropTypes.shape({
        id: PropTypes.string,
        name: PropTypes.string,
        canBeConfirmed: PropTypes.bool,
        canBeDeleted: PropTypes.bool,
        email: PropTypes.string,
        invitationDate: PropTypes.string,
        createdAt: PropTypes.string,
      })
    ),
  }),
}
export default React.memo(SchoolList)
