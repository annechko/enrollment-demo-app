import React from 'react'
import {CButton} from "@coreui/react";
import CIcon from "@coreui/icons-react";
import {cilArrowLeft} from "@coreui/icons";
import {Link} from "react-router-dom";

const AppSwitchSectionBtn = () => {
  return (
    <Link to={window.abeApp.urls.home}>
      <CButton color="dark" role="button" className="py-2 mb-4"
        size="sm"
        variant="outline">
        <CIcon icon={cilArrowLeft} className="me-2"/>
        Switch section
      </CButton>
    </Link>
  )
}

export default React.memo(AppSwitchSectionBtn)
