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
import {Link} from "react-router-dom";
import * as LoadState from "../../../App/Helper/LoadState";
import * as Api from "../../../App/Helper/Api";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";

export default function ApplicationList() {

  return <>
    <Link to={window.abeApp.urls.student_application}>
      <CButton color="primary" role="button"
        className=" pl-1 pr-1 mt-3"
      >New application
      </CButton>
    </Link>
    <Applications/>
  </>
}
const Applications = () => {
  const [applicationsState, setApplicationsState] = useState(LoadState.initialize())
  const applicationsUrl = window.abeApp.urls.api_student_application_list
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
      <CTableDataCell>{item.school.name}</CTableDataCell>
      <CTableDataCell>{item.course.name}</CTableDataCell>
      <CTableDataCell>{item.createdAt}</CTableDataCell>
      <CTableDataCell>{item.intake.startDate}</CTableDataCell>
      <CTableDataCell>{item.intake.endDate}</CTableDataCell>
      <CTableDataCell><ApplicationStatus value={item.status}/></CTableDataCell>
    </CTableRow>
  )

  return <>
    {applicationsRows.length > 0
      &&
      <CTable hover bordered>
        <CTableHead>
          <CTableRow key={key++}>
            <CTableHeaderCell scope="col">Id</CTableHeaderCell>
            <CTableHeaderCell scope="col">School</CTableHeaderCell>
            <CTableHeaderCell scope="col">Course</CTableHeaderCell>
            <CTableHeaderCell scope="col">Created</CTableHeaderCell>
            <CTableHeaderCell scope="col">Start</CTableHeaderCell>
            <CTableHeaderCell scope="col">End</CTableHeaderCell>
            <CTableHeaderCell scope="col">Status</CTableHeaderCell>
          </CTableRow>
        </CTableHead>
        <CTableBody>
          {applicationsRows}
        </CTableBody>
      </CTable>}
  </>
}
