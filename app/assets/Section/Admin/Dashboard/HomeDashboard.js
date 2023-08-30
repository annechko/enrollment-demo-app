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

const ReportLoading = ({color = 'info'}) => {
  return <CSpinner className="me-1" color={color} component="span" size="lg" aria-hidden="true"/>
}
const HomeDashboard = () => {
  const loadingSchoolsRegs = false
  const loadingApplication = false
  const schoolRegsYearRef = useRef(null);
  const schoolRegsMonthRef = useRef(null);
  const studentApplicationsMonthRef = useRef(null);
  const studentApplicationsYearRef = useRef(null);

  const [schoolsRegsYearState, setSchoolsRegsYearState] = useState(LoadState.initialize())
  const statsUrl = window.abeApp.urls.api_admin_stats
  useEffect(() => {
    if (LoadState.needLoading(schoolsRegsYearState)) {
      Api.submitData({
        state: schoolsRegsYearState,
        setState: setSchoolsRegsYearState,
        url: statsUrl,
        data: {type: 'schoolRegistrationsYear'},
        onSuccess: (response) => {
          const chart = new Chart(schoolRegsYearRef.current, {
            type: 'bar',
            data: {
              labels: response.data.labels,
              datasets: [
                {
                  label: 'Schools registrations per month',
                  backgroundColor: '#0aa4c1',
                  data: response.data.data,
                },
              ],
            },
            options: {
              scales: {
                y: {
                  max: response.data.maxY,
                }
              }
            }
          });

        }
      })
    }
  }, [schoolsRegsYearState])


  return <>
    <h4 className="mt-3">Last month</h4>
    <CRow className="mt-3">
      <CCol xs={6}>
        <CCard className="mb-4">
          <CCardHeader>School registrations</CCardHeader>
          <CCardBody>
            {
              loadingSchoolsRegs
                  ? <ReportLoading/>
                  : <canvas ref={schoolRegsMonthRef}/>
            }
          </CCardBody>
        </CCard>
      </CCol>
      <CCol xs={6}>
        <CCard className="mb-4">
          <CCardHeader>Student applications</CCardHeader>
          <CCardBody>
            {
              loadingApplication
                  ? <ReportLoading color="dark"/>
                  : <canvas ref={studentApplicationsMonthRef}/>
            }
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
            {
              loadingSchoolsRegs
                  ? <ReportLoading/>
                  : <div>
                    <canvas ref={schoolRegsYearRef}/>
                  </div>
            }
          </CCardBody>
        </CCard>
      </CCol>
      <CCol xs={6}>
        <CCard className="mb-4">
          <CCardHeader>Student applications</CCardHeader>
          <CCardBody>
            {
              loadingApplication
                  ? <ReportLoading color="dark"/>
                  : <canvas ref={studentApplicationsYearRef}/>
            }
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </>
}
export default React.memo(HomeDashboard)
