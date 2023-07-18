import React, {
  Component,
  Suspense
} from 'react'
import {
  BrowserRouter,
  Navigate,
  Route,
  Routes
} from 'react-router-dom'
import './scss/style.scss'
import {UserContext} from "./Helper/UserContext";
import {OtherAccounts} from "./Helper/OtherAccountsContext";
import AdminRoutes from '../Section/Admin/Contract/ContentRoutes';
import SchoolRoutes from '../Section/School/Contract/ContentRoutes';

const loading = (
  <div className="pt-3 text-center">
    <div className="sk-spinner sk-spinner-pulse"></div>
  </div>
)

const HomePage = React.lazy(() => import('./../App/Common/HomePage'))

class App extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const urls = window.abeApp.urls
    const currentSection = window.abeApp.currentSection
    const routes = currentSection === 'school'
      ? SchoolRoutes
      : (currentSection === 'admin' ? AdminRoutes : [])

    return (
      <UserContext.Provider value={window.abeApp.currentUser}>
        <OtherAccounts.Provider value={window.abeApp.otherAccounts}>
          <BrowserRouter>
            <Suspense fallback={loading}>
              <Routes>
                <Route exact path={urls.home} element={<HomePage/>}/>
                {routes}
                <Route path="*" element={<Navigate to={urls.home} replace/>}/>
              </Routes>
            </Suspense>
          </BrowserRouter>
        </OtherAccounts.Provider>
      </UserContext.Provider>
    )
  }
}

export default App
