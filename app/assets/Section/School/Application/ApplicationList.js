import * as React from 'react';
import {
  useEffect,
  useState
} from 'react';
import {
  CBadge,
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
  CTableRow
} from "@coreui/react";
import * as LoadState from "../../../App/Helper/LoadState";
import * as Api from "../../../App/Helper/Api";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";
import {Link} from "react-router-dom";

export default function ApplicationList() {
  const [applicationsState, setApplicationsState] = useState(LoadState.initialize())
  const applicationsUrl = window.abeApp.urls.api_school_application_list
  const loadApplications = Api.loadData(applicationsUrl, setApplicationsState)
  useEffect(() => {
    if (LoadState.needLoading(applicationsState)) {
      loadApplications()
    }
  }, [applicationsState])


  return (
    <CCard className="mb-4 mt-4">
      <CCardHeader>
        <strong>Applications</strong>
      </CCardHeader>
      <CCardBody>
        <ApplicationsRows
          applicationsState={applicationsState}
        />
      </CCardBody>
    </CCard>
  )
}

const ApplicationStatus = ({value}) => {
  if (value === 'NEW') {
    return <CBadge color="success" shape="rounded-pill">New</CBadge>
  }
  return <></>;
}

const ApplicationsRows = ({
                            applicationsState,
                          }) => {
  const applications = applicationsState.data

  let key = 0
  if (applicationsState.error !== null) {
    return <AppErrorMessage error={applicationsState.error}/>
  }
  if (applicationsState.loaded === false) {
    return <CSpinner color="primary"/>
  }
  let applicationsRows = []

  applicationsRows = applications.map((item) =>
    <CTableRow key={key++}>
      <CTableDataCell scope="row">{item.id.substring(32)}</CTableDataCell>
      <CTableDataCell>{item.student.name}</CTableDataCell>
      <CTableDataCell>{item.course.name}</CTableDataCell>
      <CTableDataCell>{item.createdAt}</CTableDataCell>
      <CTableDataCell>{item.intake.startDate + ' - ' + item.intake.endDate}</CTableDataCell>
      <CTableDataCell><ApplicationStatus value={item.status}/></CTableDataCell>
      <CTableDataCell>
        <Link to={window.abeApp.urls.school_application_edit.replace(':applicationId', item.id)}>
          <CButton color="primary" role="button"
            className="py-0 me-1"
            size="sm" variant="outline">
            View
          </CButton>
        </Link>
      </CTableDataCell>
    </CTableRow>
  )

  return <>
    {applicationsRows.length > 0
      ?
      <CTable hover bordered>
        <CTableHead>
          <CTableRow key={key++}>
            <CTableHeaderCell scope="col">Id</CTableHeaderCell>
            <CTableHeaderCell scope="col">Student</CTableHeaderCell>
            <CTableHeaderCell scope="col">Course</CTableHeaderCell>
            <CTableHeaderCell scope="col">Created</CTableHeaderCell>
            <CTableHeaderCell scope="col">Intake</CTableHeaderCell>
            <CTableHeaderCell scope="col">Status</CTableHeaderCell>
            <CTableHeaderCell scope="col">Actions</CTableHeaderCell>
          </CTableRow>
        </CTableHead>
        <CTableBody>
          {applicationsRows}
        </CTableBody>
      </CTable>
      : <>
        You have 0 applications.
      </>
    }
  </>
}
