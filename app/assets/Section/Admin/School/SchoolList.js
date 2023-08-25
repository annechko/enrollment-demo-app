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
import {cilCheck} from "@coreui/icons";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";
import PropTypes from "prop-types";
import * as LoadState from "../../../App/Helper/LoadState";
import * as Api from "../../../App/Helper/Api";
import {submitForm} from "../../../App/Helper/SubmitForm";

const SchoolList = () => {
  const [confirmState, setConfirmState] = React.useState({modalVisible: false, school: {}})

  const [schoolsState, setSchoolsState] = useState(LoadState.initialize())
  const schoolsUrl = window.abeApp.urls.api_admin_school_list
  const loadSchools = Api.loadData(schoolsUrl, setSchoolsState)
  useEffect(() => {
    if (LoadState.needLoading(schoolsState)) {
      loadSchools()
    }
  }, [schoolsState])

  const formId = 'confirm'
  const onConfirm = () => {
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
        onSuccess: onConfirm
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
        />

        <CModal visible={confirmState.modalVisible}
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
      </CCardBody>
    </CCard>
  )
}
const SchoolsRows = ({
                       schoolsState,
                       setConfirmState,
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
      <CTableDataCell >{item.email}</CTableDataCell>
      <CTableDataCell >{item.createdAt}</CTableDataCell>
      <CTableDataCell >{item.invitationDate}</CTableDataCell>
      <CTableDataCell>
        <div className="d-flex">
          {item.canBeConfirmed &&
            <CButton color="primary" role="button"
              className="py-0"
              onClick={() => {
                setConfirmState({
                  modalVisible: true,
                  school: {name: item.name, id: item.id}
                });
              }}
              size="sm" variant="outline">
              <CIcon icon={cilCheck}/>
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
            <CTableHeaderCell scope="col">Confirm</CTableHeaderCell>
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
  schoolsState: PropTypes.shape({
    data: PropTypes.arrayOf(
      PropTypes.shape({
        id: PropTypes.string,
        name: PropTypes.string,
        canBeConfirmed: PropTypes.bool,
        email: PropTypes.string,
        invitationDate: PropTypes.string,
        createdAt: PropTypes.string,
      })
    ),
  }),
}
export default React.memo(SchoolList)
