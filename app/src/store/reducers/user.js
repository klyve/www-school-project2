const initialUserState = {
    token: false,
    id: false,
    name: 'Test',
    email: '',
    usergroup: 0,
    subscriptions: [],
    playlists: [],
};

const userReducer = (state = initialUserState, action) => {
    console.log(action);
    switch(action.type) {
        case 'UPDATE_USERNAME': {
            
            return {
                ...state,
                ...action.payload
            };
        }break;
        case 'USER_LOGIN_REQUEST': {

        }break;
        case 'USER_LOGIN_FAILURE': {

        }break;
        case 'USER_LOGIN_SUCCESS': {
            // const user = Object.assign({}, state, action.payload);
            // return user;
        }break;
        default:
            return state;
    }

    return state;
};

