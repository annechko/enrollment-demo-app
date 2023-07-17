import React, {
  Component,
  Suspense
} from 'react'
import {
  BrowserRouter,
  Route,
  Routes
} from 'react-router-dom'
import './scss/style.scss'
import {UserContext} from "./Helper/UserContext";
import {OtherAccounts} from "./Helper/OtherAccountsContext";

const loading = (
  <div className="pt-3 text-center">
    <div className="sk-spinner sk-spinner-pulse"></div>
  </div>
)

const DefaultLayout = React.lazy(() => import('./Layout/DefaultLayout'))

const LoginPage = React.lazy(() => import('./Pages/Auth/LoginPage'))
const AdminLoginPage = React.lazy(() => import('./../admin/Pages/Auth/LoginPage'))
const HomePage = React.lazy(() => import('./Pages/HomePage'))
const RegisterPage = React.lazy(() => import('./Pages/Auth/RegisterPage'))

class App extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const urls = window.abeApp.urls
    return (
      <UserContext.Provider value={window.abeApp.currentUser}>
        <OtherAccounts.Provider value={window.abeApp.otherAccounts}>
          <BrowserRouter>
            <Suspense fallback={loading}>
              <Routes>
                {/* todo decide based on current section */}
                <Route exact path={urls.school_login} name="Login" element={<LoginPage urls={urls}/>}/>
                <Route exact path={urls.school_register} name="Register" element={<RegisterPage urls={urls}/>}/>
                <Route exact path={urls.admin_login} name="Login" element={<AdminLoginPage urls={urls}/>}/>

                <Route path={urls.home} name="Home" element={<HomePage/>}/>
                <Route path="*" name="Default" element={<DefaultLayout/>}/>
              </Routes>
            </Suspense>
          </BrowserRouter>
        </OtherAccounts.Provider>
      </UserContext.Provider>

    )
  }
}

export default App
