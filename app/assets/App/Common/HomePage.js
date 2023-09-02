import React from 'react'
import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CCardText,
  CCol,
  CContainer,
  CRow
} from '@coreui/react'
import { CssHelper } from '../Helper/CssHelper'
import CIcon from '@coreui/icons-react'
import { cibLinkedin } from '@coreui/icons'

const LinkCard = ({ title, color, href, text, section }) => {
  return <CCol>
    <CCard className="mb-3">
      <CCardHeader className={'text-white b g-opacity-25 ' + CssHelper.getSectionBgColor(section)}
        component="h4"
        style={{ minHeight: '50px' }}
      >{title}</CCardHeader>
      <CCardBody className={'app-home-card-body align-content-between d-flex flex-wrap b' +
        ' g-opacity-50 b g-' + color}>
        <CCardText className="content" dangerouslySetInnerHTML={{ __html: text }}>
        </CCardText>
        <CButton color={color} data-testid={`go-${section}-btn`}
          className={'text-white app-no-border ' + CssHelper.getSectionBgColor(section)}
          href={href}>Go as {title}</CButton>
      </CCardBody>
    </CCard>
  </CCol>
}
const HomePage = () => {
  // todo add TS, add constants for sections
  const classAdmin = CssHelper.getSectionTextColor('admin')
  const classSchool = CssHelper.getSectionTextColor('school')
  const classStudent = CssHelper.getSectionTextColor('student')
  return <>
    <div className="bg-light app-home-cards-wrapper d-flex flex-row align-items-center">
      <CContainer>
        <CRow className="align-items-center justify-content-center text-center mb-5 mt-5">
          <h2>Enrollment Demo Application</h2>
          <p className="app-linked">Created by Anna Borzenko
            <a href="https://www.linkedin.com/in/anna-borzenko/" target="_blank"
              className="text-decoration-none" rel="noreferrer">
              <CIcon icon={cibLinkedin} size="xl" className="mx-3"/>
            </a>
          </p>
        </CRow>
        <CRow className="align-items-center justify-content-center d-flex app-home-container">
          <LinkCard title="Admin" color="danger"
            section="admin"
            href={window.abeApp.urls.admin_login}
            text={'Log in as an <b class="' + classAdmin + '">Admin</b> user of the platform, approve requests to ' +
              'join from <b class="' + classSchool + '">Schools</b>, manage <b class="' + classSchool + '">' +
              'Schools</b> and <b class="' + classStudent + '">Students</b>, etc.'
            }
          />
          <LinkCard title="School" color="info"
            section="school"
            href={window.abeApp.urls.school_login}
            text={'Log in as a <b class="' + classSchool + '">School</b> user, manage campuses, courses, intakes,' +
              ' see all your current and potential <b class="' + classStudent + '">Students</b>, approve their' +
              ' requests to study' +
              ' at your <b class="' + classSchool + '">School</b>, send them offers of placement, etc.'
            }
          />
          <LinkCard title="Student" color="success"
            section="student"
            href={window.abeApp.urls.student_login}
            text={'Log in as a <b class="' + classStudent + '">Student</b> user, browse various <b' +
              ' class="' + classSchool + '">Schools</b> and their programms' +
              ', send your request to study at the chosen course, see your request statuses, etc.'
            }
          />
        </CRow>
      </CContainer>
    </div>
  </>
}
export default React.memo(HomePage)
