import React from 'react'
import CampusList from "./../../views/campus/CampusList";
import LoadablePage from "../LoadablePage";

const CampusListPage = () => {

  return <LoadablePage
    Component={CampusList}
    url={window.abeApp.urls.api_school_campus_list}/>
}

export default CampusListPage
