import React, {
  useEffect,
  useRef,
  useState
} from 'react'
import {
  CCard,
  CCardBody,
  CCardHeader,
  CCol,
  CRow,
  CSpinner
} from "@coreui/react";
import Chart from 'chart.js/auto';
import * as LoadState from "../../../App/Helper/LoadState";
import * as Api from "../../../App/Helper/Api";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";

const Report = ({reportRequestType, label, color}) => {
  const reportRef = useRef(null);
  const [state, setState] = useState(LoadState.initialize())
  const [loading, setLoading] = useState(true)
  const statsUrl = window.abeApp.urls.api_admin_stats
  useEffect(() => {
    if (LoadState.needLoading(state)) {
      Api.submitData({
        state: state,
        setState: setState,
        url: statsUrl,
        data: {type: reportRequestType},
        onSuccess: (response) => {
          setLoading(false)
          new Chart(reportRef.current, {
            type: 'bar',
            data: {
              labels: response.data.labels,
              datasets: [
                {
                  label: label,
                  backgroundColor: color,
                  data: response.data.data,
                },
              ],
            },
            options: {scales: {y: {max: response.data.maxY}}}
          });
        }
      })
    }
  }, [this])

  return <>
    {loading && !state.error && <ReportLoading/>}
    <AppErrorMessage error={state?.error}/>
    <canvas ref={reportRef}/>
  </>
}
const ReportLoading = ({color = 'info'}) => {
  return <CSpinner className="me-1" color={color} component="span" aria-hidden="true"/>
}

const HomeDashboard = () => {

  return <>
    <h4 className="mt-3">Last month</h4>
    <CRow className="mt-3">
      <CCol xs={6}>
        <CCard className="mb-4">
          <CCardHeader>School registrations</CCardHeader>
          <CCardBody>
            <Report reportRequestType={'schoolRegistrationsMonth'}
                label={'Schools registrations per day'} color={'#0ac17b'}
            />
          </CCardBody>
        </CCard>
      </CCol>
      <CCol xs={6}>
        <CCard className="mb-4">
          <CCardHeader>Student applications</CCardHeader>
          <CCardBody>
            <Report reportRequestType={'studentApplicationsMonth'}
                label={'Student applications per day'} color={'#a90ac1'}
            />
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
    <h4>Last year</h4>
    <CRow className="mt-3 mb-5">
      <CCol xs={6}>
        <CCard className="mb-4">
          <CCardHeader>School registrations</CCardHeader>
          <CCardBody>
            <Report reportRequestType={'schoolRegistrationsYear'}
                label={'Schools registrations per month'} color={'#0aa4c1'}
            />
          </CCardBody>
        </CCard>
      </CCol>
      <CCol xs={6}>
        <CCard className="mb-4">
          <CCardHeader>Student applications</CCardHeader>
          <CCardBody>
            <Report reportRequestType={'studentApplicationsYear'}
                label={'Student applications per month'} color={'#3e0ac1'}
            />
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </>
}
export default React.memo(HomeDashboard)
