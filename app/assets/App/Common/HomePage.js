import React from "react";
import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CCardText,
  CCol,
  CContainer,
  CRow
} from "@coreui/react";

const LinkCard = ({title, color, href, text}) => {
  return <CCol>
    <CCard className="mb-3">
      <CCardHeader className={'text-white b g-opacity-25 bg-' + color}
        component="h4"
        style={{minHeight: '50px'}}
      >{title}</CCardHeader>
      <CCardBody className={'align-content-between d-flex flex-wrap b g-opacity-50 b g-' + color} style={{minHeight: '13rem'}}>
        <CCardText className="content" dangerouslySetInnerHTML={{__html: text}}>
        </CCardText>
        <CButton color={color} className={'text-white'}
          href={href}>Go as {title}</CButton>
      </CCardBody>
    </CCard>
  </CCol>
}
const CourseForm = ({}) => {
  return <>
    <div className="bg-light min-vh-100 d-flex flex-row align-items-center">
      <CContainer>
        <CRow className="align-items-center justify-content-center">
          <LinkCard title="Admin" color="danger"
            href={window.abeApp.urls.admin_login}
            text={'Log in as an <b class="text-danger">Admin</b> user of the platform, approve requests to '
              + 'join from <b class="text-info">Schools</b>, manage <b class="text-info">Schools</b> and <b class="text-success">Students</b>, etc.'
            }
          />
          <LinkCard title="School" color="info"
            href={window.abeApp.urls.school_login}
            text={'Log in as a <b class="text-info">School</b> user, manage campuses, courses, intakes,'
              + ' see all your current and potential <b class="text-success">Students</b>, approve their' +
              ' requests to study'
              + ' at your <b class="text-info">School</b>, send them offers of placement, etc.'
            }
          />
          <LinkCard title="Student" color="success"
            href="#"
            text={'Log in as a <b class="text-success">Student</b> user, browse various <b' +
              ' class="text-info">Schools</b> and their programms'
              + ', send your request to study at the chosen course, see your request statuses, etc.'
            }
          />
        </CRow>

      </CContainer>
    </div>
  </>
}
export default React.memo(CourseForm)
