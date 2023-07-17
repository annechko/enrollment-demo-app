import React from 'react'
import {
  Route,
  Routes
} from "react-router-dom";

const urls = window.abeApp.urls

const AdminRoutes = () => {
  return (<Routes>
      <Route exact path={urls.admin_school_list} name="Home" element={<div>admin admin_school_list</div>}/>
      <Route exact path={urls.admin_home} name="Home" element={<div> admin_home</div>}/>
      {/* todo default route to home <Route path="/" element={<Navigate to="dashboard" replace/>}/>*/}
    </Routes>
  )
}
export default AdminRoutes
